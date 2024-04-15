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
use App\Modules\Teacher\Models\Teacher;
use Cache;
use Spatie\LaravelData\DataCollection;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения инструмента.
 */
class CourseTeacherReadAction extends Action
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

        if (isset($filters['teachers-id'])) {
            $teacherFilters = is_array($filters['teachers-id']) ? $filters['teachers-id'] : [$filters['teachers-id']];
            unset($filters['teachers-id']);
        } else {
            $teacherFilters = [];
        }

        if (empty($this->filters)) {
            $cacheKey = Util::getKey(
                'course',
                'teachers',
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
                    $query = Teacher::select([
                        'teachers.id',
                        'teachers.link',
                        'teachers.name',
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
                'teachers',
                'site',
                'read',
                'all',
            );

            $allTeachers = Cache::tags(['catalog', 'course'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    return Teacher::select([
                        'teachers.id',
                        'teachers.link',
                        'teachers.name',
                    ])
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value)
                        ->where('has_active_school', true);
                    })
                    ->where('status', true)
                    ->orderBy('name')
                    ->get()
                    ->toArray();
                }
            );
        } else {
            $allTeachers = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'teachers',
            'site',
            'read',
            $filters,
            $teacherFilters,
            $this->offset,
            $this->limit,
            $this->disabled,
        );

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($teacherFilters, $filters, $allTeachers) {
                if (!empty($filters) || !$this->disabled) {
                    $query = Teacher::select([
                        'teachers.id',
                        'teachers.link',
                        'teachers.name',
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

                    if (count($teacherFilters) && !$this->disabled) {
                        $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($teacherFilters)) . ')'), 'DESC');
                    }

                    if (!$this->disabled) {
                        if ($this->offset) {
                            $query->offset($this->offset);
                        }

                        if ($this->limit) {
                            $query->limit($this->limit);
                        }
                    }

                    $activeTeachers = $query->get()->toArray();
                } else {
                    $activeTeachers = $allTeachers;
                }

                if ($this->disabled) {
                    if (count($activeTeachers) !== count($allTeachers)) {
                        $activeTeachersWithKey = [];

                        for ($i = 0; $i < count($activeTeachers); $i++) {
                            $activeTeachersWithKey[$activeTeachers[$i]['id']] = true;
                        }

                        for ($i = 0; $i < count($allTeachers); $i++) {
                            $allTeachers[$i]['disabled'] = !isset($activeTeachersWithKey[$allTeachers[$i]['id']]);
                        }
                    } else {
                        $allTeachers = collect($allTeachers)->map(function ($item) {
                            $item['disabled'] = false;

                            return $item;
                        })->toArray();
                    }

                    $result = collect($allTeachers)
                        ->sortBy(function ($item) use ($teacherFilters) {
                            if (count($teacherFilters) && in_array($item['id'], $teacherFilters)) {
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

                return CourseItemFilter::collect($activeTeachers);
            }
        );
    }
}
