<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Modules\Tool\Models\Tool;
use DB;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\School\Models\School;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения школ.
 */
class CourseSchoolReadAction extends Action
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
     * Отключать не активные.
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

        if (isset($filters['school-id'])) {
            $schoolFilters = is_array($filters['school-id']) ? $filters['school-id'] : [$filters['school-id']];
            unset($filters['school-id']);
        } else {
            $schoolFilters = [];
        }

        if (empty($filters)) {
            $cacheKey = Util::getKey(
                'course',
                'schools',
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
                'school',
            ])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    $result = School::select([
                        'schools.id',
                        'schools.link',
                        'schools.name',
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
                        ->orderBy('name')
                        ->offset($this->offset)
                        ->limit($this->limit)
                        ->get()
                        ->toArray();

                    return Entity::toEntities($result, new CourseItemFilter());
                }
            );
        }

        if ($this->disabled === true) {
            $cacheKey = Util::getKey(
                'course',
                'schools',
                'site',
                'read',
                'all',
            );

            $allSchools = Cache::tags([
                'course',
                'direction',
                'profession',
                'category',
                'skill',
                'teacher',
                'school',
                'process',
                'employment',
                'school',
            ])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    return School::select([
                        'schools.id',
                        'schools.link',
                        'schools.name',
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
                    ->orderBy('name')
                    ->get()
                    ->toArray();
                }
            );
        } else {
            $allSchools = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'schools',
            'site',
            'read',
            $filters,
            $schoolFilters,
            $this->offset,
            $this->limit,
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
            'school',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($schoolFilters, $filters, $allSchools) {
                if (!empty($filters) || (count($schoolFilters) && !$this->disabled) || !$this->disabled) {
                    $query = School::select([
                        'schools.id',
                        'schools.link',
                        'schools.name',
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
                        ->orderBy('name');

                    if (count($schoolFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($schoolFilters)) . ')'), 'DESC');
                    }

                    if (!$this->disabled) {
                        if ($this->offset) {
                            $query->offset($this->offset);
                        }

                        if ($this->limit) {
                            $query->limit($this->limit);
                        }
                    }

                    $activeSchools = $query->get()->toArray();
                } else {
                    $activeSchools = $allSchools;
                }

                if ($this->disabled) {
                    if (count($activeSchools) !== count($allSchools)) {
                        $activeSchoolsWithKey = [];

                        for ($i = 0; $i < count($activeSchools); $i++) {
                            $activeSchoolsWithKey[$activeSchools[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allSchools); $i++) {
                            $allSchools[$i]['disabled'] = !isset($activeSchoolsWithKey[$allSchools[$i]['id']]);
                        }
                    } else {
                        $allSchools = collect($allSchools)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allSchools)
                        ->sortBy(function ($item) use ($schoolFilters) {
                            if (count($schoolFilters) && in_array($item['id'], $schoolFilters)) {
                                $weight = 1;
                            } else if (isset($item['disabled']) && !$item['disabled']) {
                                $weight = 2;
                            } else {
                                $weight = 3;
                            }

                            return $weight . ' - '. $item['name'];
                        })
                        ->slice($this->offset, $this->limit)
                        ->values()
                        ->toArray();

                    return Entity::toEntities($result, new CourseItemFilter());
                }

                return Entity::toEntities($activeSchools, new CourseItemFilter());
            }
        );
    }
}
