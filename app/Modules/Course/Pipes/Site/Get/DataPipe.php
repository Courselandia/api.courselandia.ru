<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Get;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Modules\Course\Data\Decorators\CourseGet;
use Closure;

/**
 * Получение курса: фильтры, очистка и подготовка данных.
 */
class DataPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseGet $data Сущность получения курса.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|CourseGet $data, Closure $next): mixed
    {
        $data->link = null;
        $data->school = null;
        $data->id = null;

        return $next($data);
    }
}
