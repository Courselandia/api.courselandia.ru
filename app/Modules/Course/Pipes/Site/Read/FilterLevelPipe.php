<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Modules\Course\Models\CourseLevel;
use Cache;
use Util;
use Closure;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Salary\Enums\Level;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;

/**
 * Чтение курсов: фильтры: уровни.
 */
class FilterLevelPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseRead $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Entity|CourseRead $entity, Closure $next): mixed
    {
        $currentFilters = $entity->filters;

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

        $entity->filter->levels = $levels;

        return $next($entity);
    }
}
