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
use App\Modules\School\Models\School;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\CourseItemFilter;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения школ.
 */
class CourseSchoolReadAction extends Action
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

        if (isset($filters['school-id'])) {
            $schoolFilters = is_array($filters['school-id']) ? $filters['school-id'] : [$filters['school-id']];
            unset($filters['school-id']);
        } else {
            $schoolFilters = [];
        }

        if ($this->disabled === true) {
            $cacheKey = Util::getKey(
                'course',
                'schools',
                'site',
                'read',
                'all',
            );

            $allSchools = Cache::tags([
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
                    $result = School::select([
                        'schools.id',
                        'schools.link',
                        'schools.name',
                    ])
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value);
                    })
                    ->where('status', true)
                    ->orderBy('name')
                    ->get()
                    ->toArray();

                    return Entity::toEntities($result, new CourseItemFilter());
                }
            );
        } else {
            $allSchools = [];
        }

        $cacheKey = Util::getKey(
            'course',
            'schools',
            'site',
            'read',
            $filters,
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
            function () use ($schoolFilters, $filters, $allSchools) {
                $query = School::select([
                    'schools.id',
                    'schools.link',
                    'schools.name',
                ])
                ->whereHas('courses', function ($query) use ($filters) {
                    $query->filter($filters ?? [])
                        ->where('status', Status::ACTIVE->value);
                })
                ->where('status', true);

                if (count($schoolFilters) && !$this->disabled) {
                    $query->orderBy(DB::raw('FIELD(id, ' . implode(', ', array_reverse($schoolFilters)) . ')'), 'DESC');
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

                $activeSchools = Entity::toEntities($result, new CourseItemFilter());

                if ($this->disabled) {
                    $collectionActiveSchools = collect($activeSchools);

                    foreach ($allSchools as $school) {
                        /**
                         * @var CourseItemFilter $school
                         */
                        $school->disabled = $collectionActiveSchools->search(function ($item) use ($school) {
                            return $item->id === $school->id;
                        }) === false;
                    }

                    return collect($allSchools)
                        ->sortBy(function ($item) {
                            $disabled = $item->disabled ? '1' : '0';

                            return $disabled . ' ' . $item->name;
                        })
                        ->slice($this->offset, $this->limit)
                        ->values()
                        ->toArray();
                }

                return $activeSchools;
            }
        );
    }
}
