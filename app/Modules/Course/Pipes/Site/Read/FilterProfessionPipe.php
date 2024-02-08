<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Data;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Actions\Site\Course\CourseProfessionReadAction;
use App\Modules\Course\Data\Decorators\CourseRead;
use Closure;
use App\Models\Contracts\Pipe;

/**
 * Чтение курсов: фильтры: професии.
 */
class FilterProfessionPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseRead $data Данные для декоратора для чтения курсов.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Data|CourseRead $data, Closure $next): mixed
    {
        $action = new CourseProfessionReadAction($data->filters, 0, $data->openedProfessions ? null : 11, true);

        $data->filter->professions = $action->run();

        return $next($data);
    }
}
