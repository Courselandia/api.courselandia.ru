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
 * Класс фильтр для таблицы особенностей курса.
 */
class CourseFeatureFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return CourseFeatureFilter Правила поиска.
     */
    public function id(int|string $id): CourseFeatureFilter
    {
        return $this->where('course_features.id', $id);
    }

    /**
     * Поиск по тексту.
     *
     * @param string $query Строка поиска.
     *
     * @return CourseFeatureFilter Правила поиска.
     */
    public function text(string $query): CourseFeatureFilter
    {
        return $this->whereLike('course_features.text', $query);
    }
}
