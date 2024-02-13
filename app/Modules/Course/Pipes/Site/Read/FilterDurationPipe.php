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
 * Чтение курсов: фильтры: продолжительность от и до.
 */
class FilterDurationPipe implements Pipe
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

        if (isset($currentFilters['duration'])) {
            unset($currentFilters['duration']);
        }

        $cacheKey = Util::getKey(
            'course',
            'duration',
            'site',
            'read',
            $currentFilters,
        );

        $duration = Cache::tags([
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
                $min = null;

                $durationMin = Course::select([
                    DB::raw('MIN(duration_rate) as duration'),
                ])
                    ->filter($currentFilters ?: [])
                    ->where('status', Status::ACTIVE->value)
                    ->where('has_active_school', true)
                    ->first()
                    ->toArray();

                if ($durationMin && $durationMin['duration']) {
                    $min = $durationMin['duration'] < 1 ? 0 : $durationMin['duration'];
                }

                $max = null;

                $durationMax = Course::select([
                    DB::raw('MAX(duration_rate) as duration'),
                ])
                    ->filter($currentFilters ?: [])
                    ->where('status', Status::ACTIVE->value)
                    ->where('has_active_school', true)
                    ->first()
                    ->toArray();

                if ($durationMax && $durationMax['duration']) {
                    $max = max($durationMax['duration'], 1);
                }

                return [
                    'min' => $min,
                    'max' => $max
                ];
            }
        );

        $data->filter->duration->min = $duration['min'];
        $data->filter->duration->max = $duration['max'];

        return $next($data);
    }
}
