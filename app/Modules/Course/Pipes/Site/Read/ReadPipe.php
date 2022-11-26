<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

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
                    'header',
                    'text',
                    'link',
                    'url',
                    'language',
                    'rating',
                    'price',
                    'price_discount',
                    'price_recurrent_price',
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
                ->sorted($entity->sorts ?: [])
                ->with([
                    'professions' => function ($query) {
                        $query->where('status', true);
                    },
                    'categories' => function ($query) {
                        $query->where('status', true);
                    },
                    'skills' => function ($query) {
                        $query->where('status', true);
                    },
                    'teachers' => function ($query) {
                        $query->where('status', true);
                    },
                    'tools' => function ($query) {
                        $query->where('status', true);
                    },
                    'school' => function ($query) {
                        $query->where('status', true);
                    },
                    'directions' => function ($query) {
                        $query->where('status', true);
                    },
                    'levels'
                ])
                ->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                });

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
