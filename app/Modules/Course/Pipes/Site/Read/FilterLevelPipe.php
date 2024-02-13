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
use App\Modules\Course\Models\CourseLevel;
use Cache;
use Util;
use Closure;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Enums\Status;
use App\Models\Contracts\Pipe;

/**
 * Чтение курсов: фильтры: уровни.
 */
class FilterLevelPipe implements Pipe
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

        if (isset($currentFilters['levels-level'])) {
            unset($currentFilters['levels-level']);
        }

        $cacheKey = Util::getKey(
            'course',
            'level',
            'site',
            'read',
            $currentFilters
        );

        $levels = Cache::tags([
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
                $result = CourseLevel::select([
                    'level',
                ])
                ->distinct()
                ->where('id', '!=', null)
                ->whereHas('course', function ($query) use ($currentFilters) {
                    $query->select([
                        'courses.id',
                    ])
                    ->filter($currentFilters ?: [])
                    ->where('status', Status::ACTIVE->value)
                    ->where('has_active_school', true);
                })
                ->get();

                return $result->pluck('level')->toArray();
            }
        );

        $data->filter->levels = $levels;

        return $next($data);
    }
}
