<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use DB;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Profession\Models\Profession;
use Cache;
use Spatie\LaravelData\DataCollection;
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

        if (isset($filters['professions-id'])) {
            $professionFilters = is_array($filters['professions-id']) ? $filters['professions-id'] : [$filters['professions-id']];
            unset($filters['professions-id']);
        } else {
            $professionFilters = [];
        }

        if (empty($this->filters)) {
            $cacheKey = Util::getKey(
                'course',
                'professions',
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
                'professions',
                'site',
                'read',
                'all',
            );

            $allProfessions = Cache::tags(['catalog', 'course'])->remember(
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
                                ->where('has_active_school', true);
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

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($professionFilters, $filters, $allProfessions) {
                if (!empty($filters) || !$this->disabled) {
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
                                ->where('has_active_school', true);
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

                    return CourseItemFilter::collect($result);
                }

                return CourseItemFilter::collect($activeProfessions);
            }
        );
    }
}
