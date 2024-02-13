<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use App\Models\Action;
use App\Modules\Tool\Models\Tool;
use Cache;

/**
 * Класс действия для удаления инструмента.
 */
class ToolDestroyAction extends Action
{
    /**
     * Массив ID инструментов.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param array $ids Массив ID инструментов.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Tool::destroy($this->ids);
            Cache::tags(['catalog', 'tool'])->flush();
        }

        return true;
    }
}
