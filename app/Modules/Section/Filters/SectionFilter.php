<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы разделов.
 */
class SectionFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return SectionFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('sections.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return SectionFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('sections.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return SectionFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('sections.status', $status);
    }
}
