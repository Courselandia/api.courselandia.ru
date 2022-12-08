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
 * Чтение курсов: фильтры: продолжительность от и до.
 */
class FilterDurationPipe implements Pipe
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
        $filters = $entity->filters;

        $cacheKey = Util::getKey(
            'course',
            'duration',
            'site',
            'read',
            $filters,
        );

        $duration = Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($filters) {
                $min = 0;

                $durationMin = Course::select([
                    DB::raw('MIN(duration_rate) as duration'),
                ])
                ->filter($filters ?: [])
                ->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                })
                ->first()
                ->toArray();

                if ($durationMin) {
                    $min = $durationMin['duration'] < 1 ? 0 : $durationMin['duration'];
                }

                $max = null;

                $durationMax = Course::select([
                    DB::raw('MAX(duration_rate) as duration'),
                ])
                ->filter($filters ?: [])
                ->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                })
                ->first()
                ->toArray();

                if ($durationMax) {
                    $max = max($durationMax['duration'], 1);
                }

                return [
                    'min' => $min,
                    'max' => $max
                ];
            }
        );

        $entity->filter->duration->min = $duration['min'];
        $entity->filter->duration->max = $duration['max'];

        return $next($entity);
    }
}
