<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Modules\Course\Entities\CourseItemDirectionFilter;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Direction\Models\Direction;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения направлений.
 */
class CourseDirectionReadAction extends Action
{
    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    public ?array $filters = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Добавить ли категории к направлениям.
     *
     * @var bool
     */
    public ?bool $withCategories = false;

    /**
     * Добавить информацию о количестве курсов в направлении.
     *
     * @var bool
     */
    public ?bool $withCount = false;

    /**
     * Отключать не активные
     *
     * @var bool
     */
    public ?bool $disabled = false;

    /**
     * Метод запуска логики.
     *
     * @return CourseItemFilter[] Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): array
    {
        $filters = $this->filters;

        if (isset($filters['directions-id'])) {
            unset($filters['directions-id']);
        }

        if (empty($this->filters)) {
            $cacheKey = Util::getKey(
                'course',
                'directions',
                'site',
                'read',
                'part',
                $this->offset,
                $this->limit,
            );

            return Cache::tags([
                'course',
                'direction',
                'profession',
                'category',
                'skill',
                'teacher',
                'tool',
                'process',
                'employment',
            ])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    $query = Direction::select([
                        'directions.id',
                        'directions.name',
                        'directions.link',
                        'directions.weight',
                    ])
                    ->whereHas('courses', function ($query) {
                        $query->select([
                            'courses.id',
                        ])
                            ->where('status', Status::ACTIVE->value)
                            ->whereHas('school', function ($query) {
                                $query->where('status', true);
                            });
                    })
                    ->where('status', true)
                    ->orderBy('weight');

                    if ($this->offset) {
                        $query->offset($this->offset);
                    }

                    if ($this->limit) {
                        $query->limit($this->limit);
                    }

                    return Entity::toEntities($query->get()->toArray(), new CourseItemFilter());
                }
            );
        }

        if ($this->disabled === true) {
            $cacheKey = Util::getKey(
                'course',
                'directions',
                'site',
                'read',
                'all',
            );

            $allDirections = Cache::tags([
                'course',
                'direction',
                'profession',
                'category',
                'skill',
                'teacher',
                'tool',
                'process',
                'employment',
            ])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($filters) {
                    return Direction::select([
                        'directions.id',
                        'directions.name',
                        'directions.link',
                        'directions.weight',
                    ])
                    ->whereHas('courses', function ($query) {
                        $query->select([
                            'courses.id',
                        ])
                        ->where('status', Status::ACTIVE->value)
                        ->whereHas('school', function ($query) {
                            $query->where('status', true);
                        });
                    })
                    ->where('status', true)
                    ->orderBy('weight')
                    ->get()
                    ->toArray();
                }
            );
        } else {
            $allDirections = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'directions',
            'site',
            'read',
            $filters,
            $this->offset,
            $this->limit,
            $this->withCategories,
            $this->withCount,
            $this->disabled,
        );

        return Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'process',
            'employment',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($filters, $allDirections) {
                if (!empty($filters) || !$this->disabled || $this->withCategories || $this->withCount) {
                    $query = Direction::select([
                        'directions.id',
                        'directions.name',
                        'directions.link',
                        'directions.weight',
                    ])
                    ->whereHas('courses', function ($query) use ($filters) {
                        $query->select([
                            'courses.id',
                        ])
                            ->filter($filters ?: [])
                            ->where('status', Status::ACTIVE->value)
                            ->whereHas('school', function ($query) {
                                $query->where('status', true);
                            });
                    })
                    ->where('status', true)
                    ->orderBy('weight');

                    if (!$this->disabled) {
                        if ($this->offset) {
                            $query->offset($this->offset);
                        }

                        if ($this->limit) {
                            $query->limit($this->limit);
                        }
                    }

                    if ($this->withCategories) {
                        $query->with('categories');
                    }

                    if ($this->withCount) {
                        $query->withCount([
                            'courses' => function ($query) {
                                $query
                                    ->filter($this->filters ?: [])
                                    ->where('courses.status', Status::ACTIVE->value)
                                    ->whereHas('school', function ($query) {
                                        $query->where('schools.status', true);
                                    });
                            }
                        ]);
                    }

                    $activeDirections = $query->get()->toArray();
                } else {
                    $activeDirections = $allDirections;
                }

                if ($this->withCount) {
                    foreach ($activeDirections as $key => $value) {
                        $activeDirections[$key]['count'] = $value['courses_count'];

                        unset($activeDirections[$key]['courses_count']);
                    }
                }

                if ($this->withCategories) {
                    return Entity::toEntities($activeDirections, new CourseItemDirectionFilter());
                }

                if ($this->disabled) {
                    if (count($activeDirections) !== count($allDirections)) {
                        $activeDirectionsWithKey = [];

                        for ($i = 0; $i < count($activeDirections); $i++) {
                            $activeDirectionsWithKey[$activeDirections[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allDirections); $i++) {
                            $allDirections[$i]['disabled'] = !isset($activeDirectionsWithKey[$allDirections[$i]['id']]);
                        }
                    } else {
                        $allDirections = collect($allDirections)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    return Entity::toEntities($allDirections, new CourseItemFilter());
                }

                return Entity::toEntities($activeDirections, new CourseItemFilter());
            }
        );
    }
}
