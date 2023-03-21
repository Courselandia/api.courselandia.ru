<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Clean;
use DB;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Teacher\Models\Teacher;
use Cache;
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

        if (isset($filters['teachers-id'])) {
            $teacherFilters = is_array($filters['teachers-id']) ? $filters['teachers-id'] : [$filters['teachers-id']];
        } else {
            $teacherFilters = [];
        }

        if ($this->disabled === true) {
            $cacheKey = Util::getKey(
                'course',
                'teachers',
                'site',
                'read',
                'all',
            );

            $allTeachers = Cache::tags([
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
                    $result = Teacher::select([
                        'teachers.id',
                        'teachers.link',
                        'teachers.name',
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
            function () use ($teacherFilters, $filters, $allTeachers) {
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
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    });
                })
                ->where('status', true);

                if (count($teacherFilters) && !$this->disabled) {
                    $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($teacherFilters)) . ')'), 'DESC');
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

                $result = Entity::toEntities($result, new CourseItemFilter());
                $activeTeachers = Clean::do($result, ['count'], true);

                if ($this->disabled) {
                    $collectionActiveTeachers = collect($activeTeachers);

                    foreach ($allTeachers as $teacher) {
                        /**
                         * @var CourseItemFilter $teacher
                         */
                        $teacher->disabled = $collectionActiveTeachers->search(function ($item) use ($teacher) {
                            return $item->id === $teacher->id;
                        }) === false;
                    }

                    return collect($allTeachers)
                        ->sortBy(function ($item) use ($teacherFilters) {
                            if (in_array($item->id, $teacherFilters)) {
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

                return $activeTeachers;
            }
        );
    }
}
