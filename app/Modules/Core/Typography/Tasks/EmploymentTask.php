<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Query\Builder;
use App\Modules\Employment\Models\Employment;

/**
 * Типографирование трудоустройство.
 */
class EmploymentTask extends Task
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
        $employments = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($employments as $employment) {
            $employment->name = Typography::process($employment->name, true);
            $employment->text = Typography::process($employment->text);

            $employment->save();

            $this->fireEvent('finished', [$employment]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Employment::orderBy('id', 'ASC');
    }
}
