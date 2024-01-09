<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Modules\Category\Entities\Category;
use App\Modules\Course\Helpers\ElasticAggregation;
use App\Modules\Direction\Entities\Direction;
use Elasticsearch;
use App\Modules\Course\Elastic\Query\ElasticQueryCourseFilter;
use Morph;
use DB;
use Closure;
use Util;
use Cache;
use App\Modules\Course\Entities\CourseFilter;
use App\Modules\Course\Entities\CourseFilterDuration;
use App\Modules\Course\Entities\CourseFilterPrice;
use App\Modules\Course\Enums\Status;
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
        Cache::flush();

        $cacheKey = Util::getKey(
            'course',
            'site',
            'read',
            $entity->sorts,
            $entity->filters,
            $entity->offset,
            $entity->limit,
            $entity->onlyWithImage,
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
                $query = new ElasticQueryCourseFilter();

                $query->setFields([
                    'id',
                    'image_big_id',
                    'image_middle_id',
                    'image_small_id',
                    'name',
                    'header',
                    'link',
                    'url',
                    'rating',
                    'price',
                    'price_old',
                    'price_recurrent',
                    'currency',
                    'duration',
                    'duration_rate',
                    'duration_unit',
                    'lessons_amount',
                    'modules_amount',
                    'school',
                ])
                ->setOffset($entity->offset ?: 0)
                ->setSorts($entity->sorts ?: [], $entity->filters ?: [])
                ->setFilters($entity->filters ?: [])
                ->addAggDirections($entity->filters ?: [])
                ->addAggCategories($entity->filters ?: [])
                ->addAggProfessions($entity->filters ?: [])
                ->addAggSchools($entity->filters ?: [])
                ->addAggTools($entity->filters ?: [])
                ->addAggSkills($entity->filters ?: [])
                ->addAggTeacher($entity->filters ?: []);

                if ($entity->limit) {
                    $query->setLimit($entity->limit);
                }

                $body = $query->getBody();

                $data = [
                    'index' => 'courses',
                    'body' => $body,
                ];

                //print_r($data);
                //exit;

                $result = Elasticsearch::search($data);

                //print_r($result);
                //exit;

                $courses = collect($result['hits']['hits'])->map(function (array $item) {
                    return $item['_source'];
                })->toArray();

                //print_r($result);
                //exit;

                //print_r($result['aggregations']);

                return [
                    'courses' => $courses,
                    'total' => $result['hits']['total']['value'],
                    'filters' => [
                        'directions' => ElasticAggregation::getAggregations($result['aggregations'], 'directions'),
                        //'categories' => ElasticAggregation::getAggregations($result['aggregations'], 'categories'),
                    ]
                ];

                /*
                $query = Course::select([
                    'id',
                    'school_id',
                    'image_middle_id',
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
                    'updated_at',
                ])
                ->filter($entity->filters ?: [])
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
                ->where('has_active_school', true);

                if ($entity->onlyWithImage) {
                    $query->where(function ($query) {
                        $query->where('image_small_id', '!=', '')
                            ->orWhereNotNull('image_small_id');
                    });
                }

                if ($entity->sorts) {
                    if (
                        !array_key_exists('relevance', $entity->sorts)
                        || (
                            isset($entity->filters['search'])
                            && $entity->filters['search']
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
                        DB::raw('MATCH(name_morphy, text_morphy) AGAINST(' . $search . ') AS relevance')
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
                ];*/
            }
        );

        //print_r($result['filters']['directions']);
        //exit;

        $entity->courses = $result['courses'];
        $entity->total = $result['total'];
        $entity->filter = new CourseFilter();
        $entity->filter->price = new CourseFilterPrice();
        $entity->filter->duration = new CourseFilterDuration();
        $entity->filter->directions = Entity::toEntities($result['filters']['directions'], new Direction());
        //$entity->filter->categories = Entity::toEntities($result['filters']['categories'], new Category());

        return $next($entity);
    }
}
