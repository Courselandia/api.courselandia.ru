<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Query\Builder;

/**
 * Типографирование курсов.
 */
class CourseTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
         return $this->getQuery()->count();
    }

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $courses = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($courses as $course) {
            $course->name = Typography::process($course->name, true);
            $course->header = Typography::process($course->header, true);
            $course->text = Typography::process($course->text);
            $course->name_morphy = Typography::process($course->name_morphy, true);
            $course->text_morphy = Typography::process($course->text_morphy, true);

            $program = $course->program;

            if ($program) {
                for ($i = 0; $i < count($program); $i++) {
                    $program[$i]['name'] = Typography::process($program[$i]['name'], true);
                    $program[$i]['text'] = Typography::process($program[$i]['text']);
                }

                $course->program = $program;
            }

            $course->save();

            $this->fireEvent('finished', [$course]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Course::orderBy('id', 'ASC');
    }
}
