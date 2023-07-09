<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Builder;

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
            $course->name = $this->typography($course->name, true);
            $course->header = $this->typography($course->header, true);
            $course->text = $this->typography($course->text);
            $course->name_morphy = $this->typography($course->name_morphy, true);
            $course->text_morphy = $this->typography($course->text_morphy, true);

            $program = $course->program;

            if ($program) {
                for ($i = 0; $i < count($program); $i++) {
                    $program[$i]['name'] = $this->typography($course->name, $program[$i]['name'], true);
                    $program[$i]['text'] = $this->typography($course->name, $program[$i]['text']);
                }

                $course->program = $program;
            }

            $course->save();

            $this->fireEvent('finished', [$course]);
        }
    }

    /**
     * Получить запрос на курсы, которым нужно произвести типографирование.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Course::where('status', Status::ACTIVE->value)
            ->orderBy('id', 'DESC');
    }
}
