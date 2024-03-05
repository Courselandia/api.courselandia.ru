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
use Util;
use Cache;
use Closure;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Models\Course;
use App\Models\Contracts\Pipe;
use App\Modules\Course\Enums\Format;

/**
 * Чтение курсов: фильтры: наличие курсов онлайн.
 */
class FilterOnlinePipe implements Pipe
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

        if (isset($currentFilters['online'])) {
            unset($currentFilters['online']);
        }

        $cacheKey = Util::getKey(
            'course',
            'online',
            'site',
            'read',
            $currentFilters,
        );

        $formats = Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($currentFilters) {
                $result = Course::select([
                    'online',
                ])
                    ->filter($currentFilters ?: [])
                    ->where('status', Status::ACTIVE->value)
                    ->where('has_active_school', true)
                    ->groupBy('online')
                    ->get();

                $data = $result->pluck('online')->toArray();
                $results = [];

                for ($i = 0; $i < count($data); $i++) {
                    if ($data[$i] === 0) {
                        $results[] = Format::OFFLINE;
                    }

                    if ($data[$i] === 1) {
                        $results[] = Format::ONLINE;
                    }
                }

                return $results;
            }
        );

        $data->filter->formats = $formats;

        return $next($data);
    }
}
