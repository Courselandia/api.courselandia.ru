<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Get;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Data\Decorators\CourseGet;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use Cache;
use Closure;
use Util;

/**
 * Получение курса: чтение курса.
 */
class GetPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseGet $data Сущность получения курса.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Data|CourseGet $data, Closure $next): mixed
    {
        $link = $data->link;
        $school = $data->school;
        $id = $data->id;

        $cacheKey = Util::getKey('course', 'site', $school, $link, $id);

        $course = Cache::tags(['catalog', 'course'])->remember(
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
                    'teachers.experiences'
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

                return $course ? CourseEntity::from($course->toArray()) : null;
            }
        );

        $data->course = $course;

        return $next($data);
    }
}
