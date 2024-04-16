<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Data;
use App\Modules\Course\Actions\Site\Course\CourseSchoolReadAction;
use App\Modules\Course\Data\Decorators\CourseRead;
use Closure;
use App\Models\Contracts\Pipe;

/**
 * Чтение курсов: фильтры: школы.
 */
class FilterSchoolPipe implements Pipe
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
        $action = new CourseSchoolReadAction($data->filters, 0, $data->openedSchools ? null : 11, true);

        $data->filter->schools = $action->run();

        return $next($data);
    }
}
