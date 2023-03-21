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
            ])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    $result = Tool::select([
                        'tools.id',
                        'tools.link',
                        'tools.name',
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

                    return Entity::toEntities($result, new CourseItemFilter());
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
        );

        return Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($toolFilters, $filters, $allTools) {
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
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    });
                })
                ->where('status', true);

                if (count($toolFilters) && !$this->disabled) {
                    $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($toolFilters)) . ')'), 'DESC');
                }

                $query->orderBy('name');

                if (!$this->disabled) {
                    if ($this->offset) {
                        $query->offset($this->offset);
                    }

                    if ($this->limit) {
                        $query->limit($this->limit);
                    }
                }

                $result = $query->get()->toArray();
                $activeTools = Entity::toEntities($result, new CourseItemFilter());

                if ($this->disabled) {
                    $collectionActiveTools = collect($activeTools);

                    foreach ($allTools as $tool) {
                        /**
                         * @var CourseItemFilter $tool
                         */
                        $tool->disabled = $collectionActiveTools->search(function ($item) use ($tool) {
                            return $item->id === $tool->id;
                        }) === false;
                    }

                    return collect($allTools)
                        ->sortBy(function ($item) use ($toolFilters) {
                            if (in_array($item->id, $toolFilters)) {
                                $weight = 1;
                            } else if (!$item->disabled) {
                                $weight = 2;
                            } else {
                                $weight = 3;
                            }

                            return $weight . ' - '. $item->name;
                        })
                        ->slice($this->offset, $this->limit)
                        ->values()
                        ->toArray();
                }

                return $activeTools;
            }
        );
    }
}
