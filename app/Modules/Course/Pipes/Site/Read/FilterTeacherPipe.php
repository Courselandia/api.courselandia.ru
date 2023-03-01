<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Actions\Site\Course\CourseTeacherReadAction;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;

/**
 * Чтение курсов: фильтры: учителя.
 */
class FilterTeacherPipe implements Pipe
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
        $action = app(CourseTeacherReadAction::class);
        $action->filters = $entity->filters;
        $action->offset = 0;
        $action->limit = 11;

        $entity->filter->teachers = $action->run();

        return $next($entity);
    }
}
