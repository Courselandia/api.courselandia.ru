<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Actions\Site\Course\CourseSchoolReadAction;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;

/**
 * Чтение курсов: фильтры: школы.
 */
class FilterSchoolPipe implements Pipe
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
        $action = app(CourseSchoolReadAction::class);
        $action->filters = $entity->filters;
        $action->offset = 0;
        $action->limit = $entity->openedSchools ? null : 11;
        $action->disabled = true;

        $entity->filter->schools = $action->run();

        return $next($entity);
    }
}
