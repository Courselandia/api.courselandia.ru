<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class SchoolFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return SchoolFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('schools.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return SchoolFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('schools.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return SchoolFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('schools.status', $status);
    }
}
