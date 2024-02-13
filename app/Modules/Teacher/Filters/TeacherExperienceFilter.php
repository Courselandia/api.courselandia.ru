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
 * Класс фильтр для таблицы опыта работы учителей.
 */
class TeacherExperienceFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return TeacherExperienceFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('teacher_experiences.id', $id);
    }

    /**
     * Поиск по месту работы.
     *
     * @param string $query Строка поиска.
     *
     * @return TeacherExperienceFilter Правила поиска.
     */
    public function place(string $query): self
    {
        return $this->whereLike('teacher_experiences.place', $query);
    }

    /**
     * Поиск по должности.
     *
     * @param string $query Строка поиска.
     *
     * @return TeacherExperienceFilter Правила поиска.
     */
    public function position(string $query): self
    {
        return $this->whereLike('teacher_experiences.position', $query);
    }
}
