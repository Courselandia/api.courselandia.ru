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
use App\Modules\Profession\Models\Profession;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения профессий.
 */
class CourseProfessionReadAction extends Action
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

        if (isset($filters['professions-id'])) {
            $professionFilters = is_array($filters['professions-id']) ? $filters['professions-id'] : [$filters['professions-id']];
            unset($filters['professions-id']);
        } else {
            $professionFilters = [];
        }

        if (empty($filters)) {
            $cacheKey = Util::getKey(
                'course',
                'professions',
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
                    $query = Profession::select([
                        'professions.id',
                        'professions.link',
                        'professions.name',
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
                'professions',
                'site',
                'read',
                'all',
            );

            $allProfessions = Cache::tags([
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
                    return Profession::select([
                        'professions.id',
                        'professions.link',
                        'professions.name',
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
            $allProfessions = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'professions',
            'site',
            'read',
            $filters,
            $professionFilters,
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
            function () use ($professionFilters, $filters, $allProfessions) {
                if (!empty($filters) || (count($professionFilters) && !$this->disabled) || !$this->disabled) {
                    $query = Profession::select([
                        'professions.id',
                        'professions.link',
                        'professions.name',
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

                    if (count($professionFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($professionFilters)) . ')'), 'DESC');
                    }

                    if (!$this->disabled) {
                        if ($this->offset) {
                            $query->offset($this->offset);
                        }

                        if ($this->limit) {
                            $query->limit($this->limit);
                        }
                    }

                    $activeProfessions = $query->get()->toArray();
                } else {
                    $activeProfessions = $allProfessions;
                }

                if ($this->disabled) {
                    if (count($activeProfessions) !== count($allProfessions)) {
                        $activeProfessionsWithKey = [];

                        for ($i = 0; $i < count($activeProfessions); $i++) {
                            $activeProfessionsWithKey[$activeProfessions[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allProfessions); $i++) {
                            $allProfessions[$i]['disabled'] = !isset($activeProfessionsWithKey[$allProfessions[$i]['id']]);
                        }
                    } else {
                        $allProfessions = collect($allProfessions)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allProfessions)
                        ->sortBy(function ($item) use ($professionFilters) {
                            if (count($professionFilters) && in_array($item['id'], $professionFilters)) {
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

                return Entity::toEntities($activeProfessions, new CourseItemFilter());
            }
        );
    }
}
