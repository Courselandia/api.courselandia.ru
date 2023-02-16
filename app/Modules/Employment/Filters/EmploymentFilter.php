<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class EmploymentFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return EmploymentFilter Правила поиска.
     */
    public function id(int|string $id): EmploymentFilter
    {
        return $this->where('employments.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return EmploymentFilter Правила поиска.
     */
    public function name(string $query): EmploymentFilter
    {
        return $this->whereLike('employments.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return EmploymentFilter Правила поиска.
     */
    public function status(bool $status): EmploymentFilter
    {
        return $this->where('employments.status', $status);
    }
}
