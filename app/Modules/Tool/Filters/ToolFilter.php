<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class ToolFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return ToolFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('tools.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return ToolFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('tools.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return ToolFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('tools.status', $status);
    }
}
