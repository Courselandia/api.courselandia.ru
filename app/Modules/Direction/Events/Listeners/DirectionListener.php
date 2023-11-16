<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Events\Listeners;

use App\Modules\Direction\Models\Direction;

/**
 * Класс обработчик событий для модели направлений.
 */
class DirectionListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param  Direction  $direction  Модель для таблицы направлений.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Direction $direction): bool
    {
        $direction->deleteRelation($direction->metatag(), $direction->isForceDeleting());
        $direction->categories()->detach();
        $direction->teachers()->detach();
        $direction->courses()->detach();
        $direction->deleteRelation($direction->analyzers(), $direction->isForceDeleting());
        $direction->deleteRelation($direction->articles(), $direction->isForceDeleting());

        return true;
    }
}
