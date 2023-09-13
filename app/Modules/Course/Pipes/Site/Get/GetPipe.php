<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Get;

use Util;
use Cache;
use Closure;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseGet;
use App\Modules\Course\Enums\Status;
use App\Models\Contracts\Pipe;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Models\Course;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Получение курса: чтение курса.
 */
class GetPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseGet $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Entity|CourseGet $entity, Closure $next): mixed
    {
        $link = $entity->link;
        $school = $entity->school;
        $id = $entity->school;

        $cacheKey = Util::getKey('course', 'site', $school, $link, $id);

        $course = Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'process',
            'employment',
            'review',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($school, $link, $id) {
                $query = Course::with([
                        'metatag',
                        'professions' => function ($query) {
                            $query->where('status', true);
                        },
                        'professions.salaries' => function ($query) {
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
                        'processes' => function ($query) {
                            $query->where('status', true);
                        },
                        'school' => function ($query) {
                            $query->where('status', true);
                        },
                        'school.faqs' => function ($query) {
                            $query->where('status', true);
                        },
                        'directions' => function ($query) {
                            $query->where('status', true);
                        },
                        'levels',
                        'learns',
                        'employments',
                        'features',
                    ])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) use ($school) {
                        $query->where('status', true);

                        if ($school) {
                            $query->where('link', $school);
                        }
                    });

                if ($link) {
                    $query->where('link', $link);
                }

                if ($id) {
                    $query->where('courses.id', $id);
                }

                $course = $query->first();

                return $course ? new CourseEntity($course->toArray()) : null;
            }
        );

        $entity->course = $course;

        return $next($entity);
    }
}
