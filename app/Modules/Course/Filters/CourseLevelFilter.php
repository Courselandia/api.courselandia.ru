<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Filters;

use App\Modules\Salary\Enums\Level;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы уровня курса.
 */
class CourseLevelFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return CourseLevelFilter Правила поиска.
     */
    public function id(int|string $id): CourseLevelFilter
    {
        return $this->where('course_levels.id', $id);
    }

    /**
     * Поиск по уровню.
     *
     * @param Level[]|Level|string[]|string $levels Уровни.
     *
     * @return CourseLevelFilter Правила поиска.
     */
    public function level(array|Level|string $levels): CourseLevelFilter
    {
        return $this->whereIn('course_levels.level', is_array($levels) ? $levels : [$levels]);
    }
}
