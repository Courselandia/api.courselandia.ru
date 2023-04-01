<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use Morph;
use DB;
use App\Modules\Course\Entities\CourseFilter;
use App\Modules\Course\Entities\CourseFilterDuration;
use App\Modules\Course\Entities\CourseFilterPrice;
use App\Modules\Course\Enums\Status;
use Closure;
use Util;
use Cache;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Models\Course;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Чтение курсов: получение.
 */
class ReadPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseRead $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Entity|CourseRead $entity, Closure $next): mixed
    {
        $cacheKey = Util::getKey(
            'course',
            'site',
            'read',
            'count',
            $entity->sorts,
            $entity->filters,
            $entity->offset,
            $entity->limit
        );

        $result = Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'processes',
            'employment',
            'review',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($entity) {
                $query = Course::select([
                    'id',
                    'school_id',
                    'image_big_id',
                    'image_middle_id',
                    'image_small_id',
                    'name',
                    'header',
                    'text',
                    'link',
                    'url',
                    'language',
                    'rating',
                    'price',
                    'price_old',
                    'price_recurrent',
                    'currency',
                    'online',
                    'employment',
                    'duration',
                    'duration_rate',
                    'duration_unit',
                    'lessons_amount',
                    'modules_amount',
                    'status',
                ])
                ->filter($entity->filters ?: [])
                ->with([
                    'professions' => function ($query) {
                        $query->select([
                            'professions.id',
                            'professions.name',
                        ])->where('status', true);
                    },
                    'categories' => function ($query) {
                        $query->select([
                            'categories.id',
                            'categories.name',
                        ])->where('status', true);
                    },
                    'skills' => function ($query) {
                        $query->select([
                            'skills.id',
                            'skills.name',
                        ])->where('status', true);
                    },
                    'teachers' => function ($query) {
                        $query->select([
                            'teachers.id',
                            'teachers.name',
                        ])->where('status', true);
                    },
                    'tools' => function ($query) {
                        $query->select([
                            'tools.id',
                            'tools.name',
                        ])->where('status', true);
                    },
                    'school' => function ($query) {
                        $query->select([
                            'schools.id',
                            'schools.name',
                            'schools.link',
                            'schools.image_logo_id',
                        ])->where('status', true);
                    },
                    'directions' => function ($query) {
                        $query->select([
                            'directions.id',
                            'directions.name',
                        ])->where('status', true);
                    },
                    'levels'
                ])
                ->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                });

                if ($entity->sorts) {
                    if (
                        !array_key_exists('relevance', $entity->sorts)
                        || (
                            isset($entity->filters['search'])
                            && $entity->filters['search']
                        )
                    ) {
                        $query->sorted($entity->sorts ?: []);
                    } else {
                        $query->orderBy('name', 'ASC');
                    }
                }

                if (isset($entity->filters['search']) && $entity->filters['search']) {
                    $search = Morph::get($entity->filters['search']);
                    $search = DB::getPdo()->quote($search);

                    $query->addSelect(
                        DB::raw('MATCH(name_morphy, text_morphy) AGAINST(' . $search . ' IN BOOLEAN MODE) AS relevance')
                    );
                }

                $queryCount = $query->clone();

                if ($entity->offset) {
                    $query->offset($entity->offset);
                }

                if ($entity->limit) {
                    $query->limit($entity->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'courses' => Entity::toEntities($items, new CourseEntity()),
                    'total' => $queryCount->count(),
                ];
            }
        );

        $entity->courses = $result['courses'];
        $entity->total = $result['total'];
        $entity->filter = new CourseFilter();
        $entity->filter->price = new CourseFilterPrice();
        $entity->filter->duration = new CourseFilterDuration();

        return $next($entity);
    }
}
