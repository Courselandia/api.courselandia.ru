<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use DB;
use Cache;
use Spatie\LaravelData\DataCollection;
use Util;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Category\Models\Category;
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
     * Отключать не активные.
     *
     * @var bool
     */
    private ?bool $disabled;

    /**
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     * @param bool|null $disabled Отключать не активные.
     */
    public function __construct(
        ?array $filters = null,
        ?int   $offset = null,
        ?int   $limit = null,
        ?bool  $disabled = null,
    )
    {
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->disabled = $disabled;
    }

    /**
     * Метод запуска логики.
     *
     * @return DataCollection Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): DataCollection
    {
        $filters = $this->filters;

        if (isset($filters['categories-id'])) {
            $categoryFilters = is_array($filters['categories-id']) ? $filters['categories-id'] : [$filters['categories-id']];
            unset($filters['categories-id']);
        } else {
            $categoryFilters = [];
        }

        if (empty($this->filters)) {
            $cacheKey = Util::getKey(
                'course',
                'categories',
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
                                ->where('has_active_school', true);
                        })
                        ->where('status', true)
                        ->orderBy('name');

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
                'categories',
                'site',
                'read',
                'all',
            );

            $allCategories = Cache::tags(['catalog', 'course'])->remember(
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
                                ->where('has_active_school', true);
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

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($categoryFilters, $filters, $allCategories) {
                if (!empty($filters) || !$this->disabled) {
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
                                ->where('has_active_school', true);
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

                            return $weight . ' - ' . $item['name'];
                        })
                        ->slice($this->offset, $this->limit)
                        ->values()
                        ->toArray();

                    return CourseItemFilter::collect($result);
                }

                return CourseItemFilter::collect($activeCategories);
            }
        );
    }
}
