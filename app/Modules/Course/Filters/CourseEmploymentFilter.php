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
 * Класс фильтр для таблицы трудоустройства после курса.
 */
class CourseEmploymentFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return CourseEmploymentFilter Правила поиска.
     */
    public function id(int|string $id): CourseEmploymentFilter
    {
        return $this->where('course_employments.id', $id);
    }

    /**
     * Поиск по тексту.
     *
     * @param string $query Строка поиска.
     *
     * @return CourseEmploymentFilter Правила поиска.
     */
    public function text(string $query): CourseEmploymentFilter
    {
        return $this->whereLike('course_employments.text', $query);
    }
}
