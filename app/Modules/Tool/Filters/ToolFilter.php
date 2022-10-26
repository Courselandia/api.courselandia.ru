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
     * @param int $id ID.
     *
     * @return ToolFilter Правила валидации.
     */
    public function id(int $id): ToolFilter
    {
        return $this->where('tools.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return ToolFilter Правила валидации.
     */
    public function name(string $query): ToolFilter
    {
        return $this->whereLike('tools.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return ToolFilter Правила валидации.
     */
    public function status(bool $status): ToolFilter
    {
        return $this->where('tools.status', $status);
    }
}
