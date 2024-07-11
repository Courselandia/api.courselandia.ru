<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Get;

use Cache;
use Closure;
use DB;
use Morph;
use Util;
use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Data\Decorators\CourseGet;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;

/**
 * Получение курса: получение похожих курсов.
 */
class SimilaritiesPipe implements Pipe
{
    /**
     * Количество курсов.
     *
     * @var int
     */
    private const LIMIT = 12;

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseGet $data Сущность получения курса.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|CourseGet $data, Closure $next): mixed
    {
        $course = $data->course;

        if ($course) {
            $cacheKey = Util::getKey('course', 'site', 'similarities', $course->id);

            $courses = Cache::tags(['catalog', 'course'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($course) {
                    $search = $course->name;

                    if (isset($course->directions[0]->id) && isset($course->categories[0]->id)) {
                        $filters = [
                            'search' => $search,
                            'directions-id' => $course->directions[0]->id,
                            'categories-id' => [$course->categories[0]->id],
                        ];

                        $search = Morph::get($search);
                        $search = DB::getPdo()->quote($search);

                        $query = Course::select([
                            'id',
                            'school_id',
                            'image_big',
                            'image_middle',
                            'image_small',
                            'name',
                            'header',
                            'header_template',
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
                            ->filter($filters)
                            ->with([
                                'school' => function ($query) {
                                    $query->select([
                                        'schools.id',
                                        'schools.name',
                                        'schools.link',
                                        'schools.image_logo_id',
                                    ])
                                    ->active()
                                    ->onlyWithCourses();
                                },
                            ])
                            ->where('status', Status::ACTIVE->value)
                            ->whereHas('school', function ($query) {
                                $query->active()
                                    ->onlyWithCourses();
                            })
                            ->where('id', '!=', $course->id)
                            ->addSelect(
                                DB::raw('MATCH(name_morphy, text_morphy) AGAINST(' . $search . ') AS relevance')
                            )
                            ->orderBy('relevance', 'DESC')
                            ->limit(self::LIMIT);

                        $items = $query->get()->toArray();

                        return CourseEntity::collect($items);
                    }

                    return CourseEntity::collect([]);
                }
            );

            $data->similarities = $courses;
        }

        return $next($data);
    }
}
