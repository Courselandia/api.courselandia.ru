<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use DB;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Category\Models\Category;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения категорий.
 */
class CourseCategoryReadAction extends Action
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

        if (isset($filters['categories-id'])) {
            $categoryFilters = is_array($filters['categories-id']) ? $filters['categories-id'] : [$filters['categories-id']];
            unset($filters['categories-id']);
        } else {
            $categoryFilters = [];
        }

        if (empty($filters)) {
            $cacheKey = Util::getKey(
                'course',
                'categories',
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
                    $query = Category::select([
                        'categories.id',
                        'categories.link',
                        'categories.name',
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
                    ->orderBy('name');

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
                'categories',
                'site',
                'read',
                'all',
            );

            $allCategories = Cache::tags([
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
                    return Category::select([
                        'categories.id',
                        'categories.link',
                        'categories.name',
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
            $allCategories = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'categories',
            'site',
            'read',
            $filters,
            $categoryFilters,
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
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($categoryFilters, $filters, $allCategories) {
                if (!empty($filters) || (count($categoryFilters) && !$this->disabled) || !$this->disabled) {
                    $query = Category::select([
                        'categories.id',
                        'categories.link',
                        'categories.name',
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

                    if (count($categoryFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($categoryFilters)) . ')'), 'DESC');
                    }

                    if (!$this->disabled) {
                        if ($this->offset) {
                            $query->offset($this->offset);
                        }

                        if ($this->limit) {
                            $query->limit($this->limit);
                        }
                    }

                    $activeCategories = $query->get()->toArray();
                } else {
                    $activeCategories = $allCategories;
                }

                if ($this->disabled) {
                    if (count($activeCategories) !== count($allCategories)) {
                        $activeCategoriesWithKey = [];

                        for ($i = 0; $i < count($activeCategories); $i++) {
                            $activeCategoriesWithKey[$activeCategories[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allCategories); $i++) {
                            $allCategories[$i]['disabled'] = !isset($activeCategoriesWithKey[$allCategories[$i]['id']]);
                        }
                    } else {
                        $allCategories = collect($allCategories)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allCategories)
                        ->sortBy(function ($item) use ($categoryFilters) {
                            if (count($categoryFilters) && in_array($item['id'], $categoryFilters)) {
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

                return Entity::toEntities($activeCategories, new CourseItemFilter());
            }
        );
    }
}
