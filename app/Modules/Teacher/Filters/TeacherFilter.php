<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы учителей.
 */
class TeacherFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return TeacherFilter Правила поиска.
     */
    public function id(int|string $id): TeacherFilter
    {
        return $this->where('teachers.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return TeacherFilter Правила поиска.
     */
    public function name(string $query): TeacherFilter
    {
        return $this->whereLike('teachers.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return TeacherFilter Правила поиска.
     */
    public function status(bool $status): TeacherFilter
    {
        return $this->where('teachers.status', $status);
    }
}
