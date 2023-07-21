<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Course\Models\CourseLearn;

/**
 * Типографирование чему научишься на курсе.
 */
class CourseLearnTask extends Task
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
        $courseLearns = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($courseLearns as $courseLearn) {
            $courseLearn->text = Typography::process($courseLearn->text, true);

            $courseLearn->save();

            $this->fireEvent('finished', [$courseLearn]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return CourseLearn::orderBy('id', 'ASC');
    }
}
