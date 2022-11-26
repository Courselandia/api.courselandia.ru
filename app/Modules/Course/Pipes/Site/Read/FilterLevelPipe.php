<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

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
        $filters = $entity->filters;

        $cacheKey = Util::getKey(
            'course',
            'level',
            'site',
            'read',
            $filters
        );

        $levels = Cache::tags([
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
                $items = Course::select('id')
                    ->filter($filters ?: [])
                    ->with([
                        'levels' => function ($query) {
                            $query->select([
                                'course_id',
                                'level',
                            ])->where('id', '!=', null);
                        }
                    ])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    })->get();

                $result = [];

                foreach ($items as $item) {
                    foreach ($item->levels as $level) {
                        if (!isset($result[$level->level])) {
                            $result[$level->level] = Level::from($level->level);
                        }
                    }
                }

                return collect($result)->values()->toArray();
            }
        );

        $entity->filter->levels = $levels;

        return $next($entity);
    }
}
