<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Get;

use Morph;
use DB;
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
     * @param Entity|CourseGet $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Entity|CourseGet $entity, Closure $next): mixed
    {
        $course = $entity->course;

        if ($course) {
            $cacheKey = Util::getKey('course', 'site', 'similarities', $course->id);

            $courses = Cache::tags([
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
                            'image_big_id',
                            'image_middle_id',
                            'image_small_id',
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
                                    ])->where('status', true);
                                },
                            ])
                            ->where('status', Status::ACTIVE->value)
                            ->whereHas('school', function ($query) {
                                $query->where('status', true);
                            })
                            ->where('id', '!=', $course->id)
                            ->addSelect(
                                DB::raw('MATCH(name_morphy, text_morphy) AGAINST(' . $search . ') AS relevance')
                            )
                            ->orderBy('relevance', 'DESC')
                            ->limit(self::LIMIT);

                        $items = $query->get()->toArray();

                        return Entity::toEntities($items, new CourseEntity());
                    }

                    return [];
                }
            );

            $entity->similarities = $courses;
        }

        return $next($entity);
    }
}
