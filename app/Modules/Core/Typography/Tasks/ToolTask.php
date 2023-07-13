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
use App\Modules\Tool\Models\Tool;

/**
 * Типографирование инструментов.
 */
class ToolTask extends Task
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
        $tools = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($tools as $tool) {
            $tool->name = Typography::process($tool->name, true);
            $tool->header = Typography::process($tool->header, true);
            $tool->text = Typography::process($tool->text);

            $tool->save();

            $this->fireEvent('finished', [$tool]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Tool::orderBy('id', 'ASC');
    }
}
