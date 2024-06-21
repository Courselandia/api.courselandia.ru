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
use Util;
use Storage;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Enums\Status;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Entities\CourseItemFilter;

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
     * Позволить брать данные с файлов.
     *
     * @var bool
     */
    private bool $takeFromFiles;

    /**
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     * @param bool|null $disabled Отключать не активные.
     * @param bool $takeFromFiles Позволить брать данные с файлов.
     */
    public function __construct(
        ?array $filters = null,
        ?int $offset = null,
        ?int $limit = null,
        ?bool $disabled = null,
        bool $takeFromFiles = false,
    ) {
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->disabled = $disabled;
        $this->takeFromFiles = $takeFromFiles;
    }

    /**
     * Метод запуска логики.
     *
     * @return array<int, CourseItemFilter> Вернет результаты исполнения.
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

        if (empty($this->filters)) {
            return $this->getPartitionCategories();
        }

        $allCategories = [];

        if ($this->disabled === true) {
            $allCategories = $this->getAllProfessions();
        }

        return $this->getCategories($filters, $categoryFilters, $allCategories);
    }

    /**
     * Получить часть категорий без учета фильтра.
     *
     * @return array<int, CourseItemFilter> Вернет массив фильтров.
     */
    private function getPartitionCategories(): array
    {
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

    /**
     * Вернет все категории.
     *
     * @return array Массив всех категорий.
     */
    private function getAllProfessions(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'categories',
            'site',
            'read',
            'all',
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $path = '/json/categories.json';

                if ($this->takeFromFiles && Storage::drive('public')->exists($path)) {
                    $data = Storage::drive('public')->get($path);

                    return json_decode($data, true);
                }

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
    }

    /**
     * Получить категории с учетом фильтров.
     *
     * @param array $filters Все фильтры.
     * @param array $categoryFilters Фильтры категорий.
     * @param array $allCategories Все категории.
     *
     * @return array<int, CourseItemFilter> Вернет массив фильтров.
     */
    private function getCategories(array $filters, array $categoryFilters, array $allCategories): array
    {
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
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($categoryFilters)) . ')'),
                            'DESC');
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
                            } else {
                                if (isset($item['disabled']) && !$item['disabled']) {
                                    $weight = 2;
                                } else {
                                    $weight = 3;
                                }
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
