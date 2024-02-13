<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class CategoryFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return CategoryFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('categories.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return CategoryFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('categories.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return CategoryFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('categories.status', $status);
    }
}
