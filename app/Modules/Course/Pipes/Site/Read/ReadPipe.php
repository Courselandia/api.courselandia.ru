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
use Closure;
use Util;
use Cache;
use App\Modules\Course\Entities\CourseFilter;
use App\Modules\Course\Entities\CourseFilterDuration;
use App\Modules\Course\Entities\CourseFilterPrice;
use App\Modules\Course\Enums\Status;
use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Data\Decorators\CourseRead;
use App\Modules\Course\Models\Course;
use App\Modules\Term\Actions\Site\TermQuerySearchAction;

/**
 * Чтение курсов: получение.
 */
class ReadPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseRead $data Данные для декоратора для чтения курсов.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|CourseRead $data, Closure $next): mixed
    {
        $cacheKey = Util::getKey(
            'course',
            'site',
            'read',
            $data->sorts,
            $data->filters,
            $data->offset,
            $data->limit,
            $data->onlyWithImage,
            $data->onlyCount ? 'count' : 'all',
        );

        $result = Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($data) {
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
                    'program',
                    'status',
                    'updated_at',
                ])
                ->filter($data->filters ?: [])
                ->with([
                    'school' => function ($query) {
                        $query->select([
                            'schools.id',
                            'schools.name',
                            'schools.link',
                            'schools.image_logo_id',
                        ])->where('status', true);
                    },
                    'school.promocode' => function ($query) {
                        $query->applicable();
                    },
                    'learns',
                    'tools' => function ($query) {
                        $query->where('status', true);
                    },
                    'teachers' => function ($query) {
                        $query->select([
                            'teachers.id',
                            'teachers.name',
                            'teachers.link',
                            'teachers.copied',
                            'teachers.city',
                            'teachers.comment',
                            'teachers.additional',
                            'teachers.text',
                            'teachers.rating',
                            'teachers.status',
                            'teachers.image_middle_id',
                        ])->where('status', true);
                    },
                    'teachers.experiences',
                ])
                ->where('status', Status::ACTIVE->value)
                ->where('has_active_school', true);

                if ($data->onlyWithImage) {
                    $query->where(function ($query) {
                        $query->where('image_small_id', '!=', '')
                            ->orWhereNotNull('image_small_id');
                    });
                }

                if ($data->sorts) {
                    if (
                        isset($data->sorts['relevance'])
                        && isset($data->filters['search'])
                        && $data->filters['search']
                    ) {
                        $columnSort = DB::raw('relevance_name + relevance');
                        $query->orderBy($columnSort, 'DESC');
                    } else if ($data->sorts && !isset($data->sorts['relevance'])) {
                        $query->sorted($data->sorts);
                    } else {
                        $query->orderBy('name', 'ASC');
                    }
                }

                if (isset($data->filters['search']) && $data->filters['search']) {
                    $action = new TermQuerySearchAction($data->filters['search']);
                    $queryTerm = $action->run();
                    $search = DB::getPdo()->quote($queryTerm);

                    $query->addSelect(
                        DB::raw('MATCH(name_morphy, text_morphy) AGAINST(' . $search . ') AS relevance'),
                        DB::raw('MATCH(name_morphy) AGAINST(' . $search . ') AS relevance_name'),
                        DB::raw('MATCH(text_morphy) AGAINST(' . $search . ') AS relevance_text'),
                    );
                }

                $queryCount = $query->clone();

                if ($data->offset) {
                    $query->offset($data->offset);
                }

                if ($data->limit) {
                    $query->limit($data->limit);
                }

                if ($data->onlyCount) {
                    return $query->count();
                }

                $items = $query->get()->toArray();

                return [
                    'courses' => CourseEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );

        if ($data->onlyCount) {
            return $result;
        }

        $data->courses = $result['courses'];
        $data->total = $result['total'];
        $data->filter = new CourseFilter();
        $data->filter->price = new CourseFilterPrice();
        $data->filter->duration = new CourseFilterDuration();

        return $next($data);
    }
}
