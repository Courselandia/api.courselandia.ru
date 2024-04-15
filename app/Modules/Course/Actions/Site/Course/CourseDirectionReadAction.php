<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Modules\Course\Entities\CourseItemDirectionFilter;
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
    private ?array $filters;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    private ?int $offset;

    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * Добавить ли категории к направлениям.
     *
     * @var bool
     */
    private ?bool $withCategories;

    /**
     * Добавить информацию о количестве курсов в направлении.
     *
     * @var bool
     */
    private ?bool $withCount;

    /**
     * Отключать не активные.
     *
     * @var bool
     */
    private ?bool $disabled;

    /**
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки.
     * @param bool|null $withCategories Добавить ли категории к направлениям.
     * @param bool|null $withCount Отключать не активные.
     * @param bool|null $disabled Отключать не активные.
     */
    public function __construct(
        ?array $filters = null,
        ?int   $offset = null,
        ?int   $limit = null,
        ?bool  $withCategories = null,
        ?bool  $withCount = null,
        ?bool  $disabled = null
    )
    {
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->withCategories = $withCategories;
        $this->withCount = $withCount;
        $this->disabled = $disabled;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): array
    {
        $filters = $this->filters;

        if (isset($filters['directions-id'])) {
            unset($filters['directions-id']);
        }

        if (empty($this->filters) && !$this->withCategories) {
            $cacheKey = Util::getKey(
                'course',
                'directions',
                'site',
                'read',
                'part',
                $this->offset,
                $this->limit,
            );

            return Cache::tags(['catalog', 'course'])->remember(
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
                                ->where('has_active_school', true);
                        })
                        ->where('status', true)
                        ->orderBy('weight');

                    if ($this->offset) {
                        $query->offset($this->offset);
                    }

                    if ($this->limit) {
                        $query->limit($this->limit);
                    }

                    return CourseItemFilter::collect($query->get()->toArray());
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

            $allDirections = Cache::tags(['catalog', 'course'])->remember(
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
                                ->where('has_active_school', true);
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

        return Cache::tags(['catalog', 'course'])->remember(
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
                                ->where('has_active_school', true);
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
                                    ->where('has_active_school', true);
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
                    return CourseItemDirectionFilter::collect($activeDirections);
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

                    return CourseItemFilter::collect($allDirections);
                }

                return CourseItemFilter::collect($activeDirections);
            }
        );
    }
}
