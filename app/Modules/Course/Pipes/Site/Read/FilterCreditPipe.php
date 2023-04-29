<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Modules\Course\Enums\Status;
use Util;
use Cache;
use Closure;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Models\Course;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;

/**
 * Чтение курсов: фильтры: наличие кредита.
 */
class FilterCreditPipe implements Pipe
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

        if (isset($currentFilters['credit'])) {
            unset($currentFilters['credit']);
        }

        $cacheKey = Util::getKey(
            'course',
            'credit',
            'site',
            'read',
            $currentFilters,
        );

        $has = Cache::tags([
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
                return !!Course::filter($currentFilters ?: [])
                    ->whereNotNull('price_recurrent')
                    ->where('price_recurrent', '!=', 0)
                    ->where('status', Status::ACTIVE->value)
                    ->where('has_active_school', true)
                    ->count();
            }
        );

        $entity->filter->credit = $has;

        return $next($entity);
    }
}
