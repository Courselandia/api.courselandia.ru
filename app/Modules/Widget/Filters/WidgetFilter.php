<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы виджетов.
 */
class WidgetFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return WidgetFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('widgets.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return WidgetFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('widgets.name', $query);
    }

    /**
     * Поиск по индексу.
     *
     * @param string $query Строка поиска.
     *
     * @return WidgetFilter Правила поиска.
     */
    public function index(string $query): self
    {
        return $this->whereLike('widgets.index', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return WidgetFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('widgets.status', $status);
    }
}
