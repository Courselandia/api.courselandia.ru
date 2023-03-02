<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Modules\Course\Enums\Status;
use DB;
use Util;
use Cache;
use Closure;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Models\Course;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;

/**
 * Чтение курсов: фильтры: цена от и до.
 */
class FilterPricePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseRead $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|CourseRead $entity, Closure $next): mixed
    {
        $currentFilters = $entity->filters;

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
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                })
                ->first()
                ->toArray();
            }
        );

        if ($price) {
            $entity->filter->price->min = $price['price_min'];
            $entity->filter->price->max = $price['price_max'];
        } else {
            $entity->filter->price->min = 0;
            $entity->filter->price->max = 500000;
        }

        return $next($entity);
    }
}
