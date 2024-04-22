<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Modules\Term\Actions\Site\TermQuerySearchAction;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Modules\Course\Data\Decorators\CourseRead;

/**
 * Чтение курсов: пометим найденные слова в курсе.
 */
class MarkTestWithSearchQueryPipe implements Pipe
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
        if (isset($data->filters['search'])) {
            $action = new TermQuerySearchAction($data->filters['search']);
            $queryTerm = $action->run();

            for ($i = 0; $i < count($data->courses); $i++) {
                $data->courses[$i]->text = $this->mark($data->courses[$i]->text, $queryTerm);
                $data->courses[$i]->name = $this->mark($data->courses[$i]->name, $queryTerm);
                $data->courses[$i]->header = $this->mark($data->courses[$i]->header, $queryTerm);
            }
        }

        return $next($data);
    }

    /**
     * Отметить найденные слова в тексте.
     *
     * @param string $text Текст поиска.
     * @param string $query Строка поиска.
     *
     * @return string Вернет текст с отмеченными найденными словами.
     */
    private function mark(string $text, string $query): string
    {
        $queries = explode(' ', $query);

        for ($i = 0; $i < count($queries); $i++) {
            $text = str_ireplace($queries[$i], '<span>' . $queries[$i] . '</span>', $text);

            $queryDashed = explode('-', $queries[$i]);

            for ($j = 0; $j < count($queryDashed); $j++) {
                $text = str_ireplace($queryDashed[$j], '<span>' . $queryDashed[$j] . '</span>', $text);
            }
        }

        return $this->clean($text);
    }

    /**
     * Очистка текста от ненужных выделений.
     *
     * @param string $text Текст для очистки.
     * @return string Очищенный текст.
     */
    private function clean(string $text): string
    {
        $text = str_ireplace('</span><span>', '', $text);
        $text = str_ireplace('<span><span>', '<span>', $text);
        $text = str_ireplace('<span><span>', '<span>', $text);
        $text = str_ireplace('</span></span>', '</span>', $text);
        $text = str_ireplace('</span></span>', '</span>', $text);

        return str_ireplace('</span>-<span>', '-', $text);
    }
}
