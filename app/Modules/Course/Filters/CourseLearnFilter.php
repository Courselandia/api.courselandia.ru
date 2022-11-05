<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы что будет изучено на курсе.
 */
class CourseLearnFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return CourseLearnFilter Правила поиска.
     */
    public function id(int|string $id): CourseLearnFilter
    {
        return $this->where('course_learns.id', $id);
    }

    /**
     * Поиск по тексту.
     *
     * @param string $query Строка поиска.
     *
     * @return CourseLearnFilter Правила поиска.
     */
    public function text(string $query): CourseLearnFilter
    {
        return $this->whereLike('course_learns.text', $query);
    }
}
