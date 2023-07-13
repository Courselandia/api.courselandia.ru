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
use App\Modules\School\Models\School;

/**
 * Типографирование школ.
 */
class SchoolTask extends Task
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
        $schools = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($schools as $school) {
            $school->name = Typography::process($school->name, true);
            $school->header = Typography::process($school->header, true);
            $school->text = Typography::process($school->text);

            $school->save();

            $this->fireEvent('finished', [$school]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return School::orderBy('id', 'ASC');
    }
}
