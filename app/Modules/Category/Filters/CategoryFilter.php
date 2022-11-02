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
     * @param int $id ID.
     *
     * @return CategoryFilter Правила поиска.
     */
    public function id(int $id): CategoryFilter
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
    public function name(string $query): CategoryFilter
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
    public function status(bool $status): CategoryFilter
    {
        return $this->where('categories.status', $status);
    }
}
