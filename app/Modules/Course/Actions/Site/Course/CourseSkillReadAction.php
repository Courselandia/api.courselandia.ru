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
use App\Modules\Skill\Models\Skill;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения навыков.
 */
class CourseSkillReadAction extends Action
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

        if (isset($filters['skills-id'])) {
            $skillFilters = is_array($filters['skills-id']) ? $filters['skills-id'] : [$filters['skills-id']];
            unset($filters['skills-id']);
        } else {
            $skillFilters = [];
        }

        if (empty($this->filters)) {
            $cacheKey = Util::getKey(
                'course',
                'skills',
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
                    $query = Skill::select([
                        'skills.id',
                        'skills.link',
                        'skills.name',
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
                'skills',
                'site',
                'read',
                'all',
            );

            $allSkills = Cache::tags([
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
                    return Skill::select([
                        'skills.id',
                        'skills.link',
                        'skills.name',
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
            $allSkills = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'skills',
            'site',
            'read',
            $filters,
            $skillFilters,
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
            function () use ($skillFilters, $filters, $allSkills) {
                if (!empty($filters) || (count($skillFilters) && !$this->disabled) || !$this->disabled) {
                    $query = Skill::select([
                        'skills.id',
                        'skills.link',
                        'skills.name',
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

                    if (count($skillFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($skillFilters)) . ')'), 'DESC');
                    }

                    if (!$this->disabled) {
                        if ($this->offset) {
                            $query->offset($this->offset);
                        }

                        if ($this->limit) {
                            $query->limit($this->limit);
                        }
                    }

                    $activeSkills = $query->get()->toArray();
                } else {
                    $activeSkills = $allSkills;
                }

                if ($this->disabled) {
                    if (count($activeSkills) !== count($allSkills)) {
                        $activeSkillsWithKey = [];

                        for ($i = 0; $i < count($activeSkills); $i++) {
                            $activeSkillsWithKey[$activeSkills[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allSkills); $i++) {
                            $allSkills[$i]['disabled'] = !isset($activeSkillsWithKey[$allSkills[$i]['id']]);
                        }
                    } else {
                        $allSkills = collect($allSkills)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allSkills)
                        ->sortBy(function ($item) use ($skillFilters) {
                            if (count($skillFilters) && in_array($item['id'], $skillFilters)) {
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

                return Entity::toEntities($activeSkills, new CourseItemFilter());
            }
        );
    }
}
