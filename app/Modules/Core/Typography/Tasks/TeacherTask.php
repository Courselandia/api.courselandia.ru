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
use App\Modules\Teacher\Models\Teacher;

/**
 * Типографирование учителей.
 */
class TeacherTask extends Task
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
        $teachers = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($teachers as $teacher) {
            $teacher->name = Typography::process($teacher->name, true);
            $teacher->text = Typography::process($teacher->text);

            $teacher->save();

            $this->fireEvent('finished', [$teacher]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Teacher::orderBy('id', 'ASC');
    }
}
