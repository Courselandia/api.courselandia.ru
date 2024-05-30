<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Events\Listeners;

use App\Modules\Widget\Models\Widget;

/**
 * Класс обработчик событий для модели виджетов.
 */
class WidgetListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param Widget $widget Модель для таблицы виджетов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Widget $widget): bool
    {
        $widget->deleteRelation($widget->values(), $widget->isForceDeleting());

        return true;
    }
}
