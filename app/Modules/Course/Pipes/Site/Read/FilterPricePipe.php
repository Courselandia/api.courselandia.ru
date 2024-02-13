<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Data;
use App\Modules\Course\Data\Decorators\CourseRead;
use App\Modules\Course\Enums\Status;
use DB;
use Util;
use Cache;
use Closure;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Models\Course;
use App\Models\Contracts\Pipe;

/**
 * Чтение курсов: фильтры: цена от и до.
 */
class FilterPricePipe implements Pipe
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
        $currentFilters = $data->filters;

        if (isset($currentFilters['price'])) {
            unset($currentFilters['price']);
        }

        $cacheKey = Util::getKey(
            'course',
            'price',
            'site',
            'read',
            $currentFilters,
        );

        $price = Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'processes',
            'employment',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($currentFilters) {
                return Course::select([
                    DB::raw('MIN(price) as price_min'),
                    DB::raw('MAX(price) as price_max'),
                ])
                ->filter($currentFilters ?: [])
                ->where('status', Status::ACTIVE->value)
                ->where('has_active_school', true)
                ->first()
                ->toArray();
            }
        );

        if ($price) {
            $data->filter->price->min = $price['price_min'];
            $data->filter->price->max = $price['price_max'];
        } else {
            $data->filter->price->min = 0;
            $data->filter->price->max = 500000;
        }

        return $next($data);
    }
}
