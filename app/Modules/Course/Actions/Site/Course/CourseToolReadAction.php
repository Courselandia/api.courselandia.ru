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
use App\Modules\Tool\Models\Tool;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Entities\CourseItemFilter;

/**
 * Класс действия для получения инструмента.
 */
class CourseToolReadAction extends Action
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

        if (isset($filters['tools-id'])) {
            $toolFilters = is_array($filters['tools-id']) ? $filters['tools-id'] : [$filters['tools-id']];
            unset($filters['tools-id']);
        } else {
            $toolFilters = [];
        }

        if (empty($this->filters)) {
            return $this->getPartitionTools();
        }

        if ($this->disabled === true) {
            $allTools = $this->getAllTools();
        }

        return $this->getTools($filters, $toolFilters, $allTools);
    }

    /**
     * Получить часть инструментов без учета фильтра.
     *
     * @return array<int, CourseItemFilter> Вернет массив фильтров.
     */
    private function getPartitionTools(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'tools',
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
                $query = Tool::select([
                    'tools.id',
                    'tools.link',
                    'tools.name',
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

                $result = $query->get()->toArray();

                return CourseItemFilter::collect($result);
            }
        );
    }

    /**
     * Вернет все инструменты.
     *
     * @return array Массив всех инструментов.
     */
    private function getAllTools(): array
    {
        $cacheKey = Util::getKey(
            'course',
            'tools',
            'site',
            'read',
            'all',
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $path = '/json/tools.json';

                if ($this->takeFromFiles && Storage::drive('public')->exists($path)) {
                    $data = Storage::drive('public')->get($path);

                    return json_decode($data, true);
                }

                return Tool::select([
                    'tools.id',
                    'tools.link',
                    'tools.name',
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
     * Получить инструменты с учетом фильтров.
     *
     * @param array $filters Все фильтры.
     * @param array $toolFilters Фильтры инструментов.
     * @param array $allTools Все инструменты.
     * @return array<int, CourseItemFilter> Вернет массив фильтров.
     */
    private function getTools(array $filters, array $toolFilters, array $allTools): array
    {
        $cacheKey = Util::getKey(
            'course',
            'tools',
            'site',
            'read',
            $filters,
            $toolFilters,
            $this->offset,
            $this->limit,
            $this->disabled,
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($toolFilters, $filters, $allTools) {
                if (!empty($filters) || !$this->disabled) {
                    $query = Tool::select([
                        'tools.id',
                        'tools.link',
                        'tools.name',
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

                    if (count($toolFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($toolFilters)) . ')'),
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

                    $activeTools = $query->get()->toArray();
                } else {
                    $activeTools = $allTools;
                }

                if ($this->disabled) {
                    if (count($activeTools) !== count($allTools)) {
                        $activeToolsWithKey = [];

                        for ($i = 0; $i < count($activeTools); $i++) {
                            $activeToolsWithKey[$activeTools[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allTools); $i++) {
                            $allTools[$i]['disabled'] = !isset($activeToolsWithKey[$allTools[$i]['id']]);
                        }
                    } else {
                        $allTools = collect($allTools)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allTools)
                        ->sortBy(function ($item) use ($toolFilters) {
                            if (count($toolFilters) && in_array($item['id'], $toolFilters)) {
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

                return CourseItemFilter::collect($activeTools);
            }
        );
    }
}
