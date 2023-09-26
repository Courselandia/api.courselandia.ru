<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Get;

use Closure;
use App\Modules\Course\Entities\CourseGet;
use App\Models\Contracts\Pipe;
use App\Models\Entity;

/**
 * Получение курса: фильтры, очистка и подготовка данных.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseGet $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|CourseGet $entity, Closure $next): mixed
    {
        unset($entity->link);
        unset($entity->school);
        unset($entity->id);

        return $next($entity);
    }
}
