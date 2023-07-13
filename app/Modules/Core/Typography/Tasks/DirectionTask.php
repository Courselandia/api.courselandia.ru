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
use App\Modules\Direction\Models\Direction;

/**
 * Типографирование направлений.
 */
class DirectionTask extends Task
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
        $directions = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($directions as $direction) {
            $direction->name = Typography::process($direction->name, true);
            $direction->header = Typography::process($direction->header, true);
            $direction->text = Typography::process($direction->text);

            $direction->save();

            $this->fireEvent('finished', [$direction]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Direction::orderBy('id', 'ASC');
    }
}
