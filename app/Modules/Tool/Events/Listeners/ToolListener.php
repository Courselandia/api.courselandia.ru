<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Events\Listeners;

use App\Modules\Tool\Models\Tool;

/**
 * Класс обработчик событий для модели инструментов.
 */
class ToolListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param  Tool  $tool  Модель для таблицы инструментов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Tool $tool): bool
    {
        $tool->deleteRelation($tool->metatag(), $tool->isForceDeleting());
        $tool->courses()->detach();
        $tool->deleteRelation($tool->analyzers(), $tool->isForceDeleting());
        $tool->deleteRelation($tool->articles(), $tool->isForceDeleting());

        return true;
    }
}
