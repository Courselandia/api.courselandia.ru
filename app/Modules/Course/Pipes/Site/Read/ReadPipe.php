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
                    'learns',
                    'tools' => function ($query) {
                        $query->where('status', true);
                    },
                    'teachers' => function ($query) {
                        $query->where('status', true);
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
                        !array_key_exists('relevance', $data->sorts)
                        || (
                            isset($data->filters['search'])
                            && $data->filters['search']
                            && $data->filters['search']
                        )
                    ) {
                        $query->sorted($data->sorts);
                    } else {
                        $query->orderBy('name', 'ASC');
                    }
                }

                if (isset($data->filters['search']) && $data->filters['search']) {
                    $search = Morph::get($data->filters['search']);
                    $search = DB::getPdo()->quote($search);

                    $query->addSelect(
                        DB::raw('MATCH(name_morphy, text_morphy) AGAINST(' . $search . ') AS relevance')
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
