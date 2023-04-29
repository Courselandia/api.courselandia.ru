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
use App\Modules\Course\Enums\Format;

/**
 * Чтение курсов: фильтры: наличие курсов онлайн.
 */
class FilterOnlinePipe implements Pipe
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

        $formats = Cache::tags([
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
                $result = Course::select([
                    'online',
                ])->filter($currentFilters ?: [])
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

        $entity->filter->formats = $formats;

        return $next($entity);
    }
}
