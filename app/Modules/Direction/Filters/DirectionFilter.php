<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class DirectionFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return DirectionFilter Правила поиска.
     */
    public function id(int|string $id): DirectionFilter
    {
        return $this->where('directions.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return DirectionFilter Правила поиска.
     */
    public function name(string $query): DirectionFilter
    {
        return $this->whereLike('directions.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return DirectionFilter Правила поиска.
     */
    public function status(bool $status): DirectionFilter
    {
        return $this->where('directions.status', $status);
    }
}
