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
use App\Modules\Tool\Models\Tool;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

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

        if (isset($filters['tools-id'])) {
            $toolFilters = is_array($filters['tools-id']) ? $filters['tools-id'] : [$filters['tools-id']];
            unset($filters['tools-id']);
        } else {
            $toolFilters = [];
        }

        if (empty($this->filters)) {
            $cacheKey = Util::getKey(
                'course',
                'tools',
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

                    return Entity::toEntities($result, new CourseItemFilter());
                }
            );
        }

        if ($this->disabled === true) {
            $cacheKey = Util::getKey(
                'course',
                'tools',
                'site',
                'read',
                'all',
            );

            $allTools = Cache::tags([
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
        } else {
            $allTools = [];
        }

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
            function () use ($toolFilters, $filters, $allTools) {
                if (!empty($filters) || (count($toolFilters) && !$this->disabled) || !$this->disabled) {
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
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($toolFilters)) . ')'), 'DESC');
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

                return Entity::toEntities($activeTools, new CourseItemFilter());
            }
        );
    }
}
