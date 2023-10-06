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
 * Класс фильтр для таблицы социальных сетей учителей.
 */
class TeacherSocialMediaFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return TeacherSocialMediaFilter Правила поиска.
     */
    public function id(int|string $id): TeacherSocialMediaFilter
    {
        return $this->where('teacher_social_medias.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return TeacherSocialMediaFilter Правила поиска.
     */
    public function place(string $query): TeacherSocialMediaFilter
    {
        return $this->whereLike('teacher_social_medias.name', $query);
    }

    /**
     * Поиск по значению.
     *
     * @param string $query Строка поиска.
     *
     * @return TeacherSocialMediaFilter Правила поиска.
     */
    public function position(string $query): TeacherSocialMediaFilter
    {
        return $this->whereLike('teacher_social_medias.value', $query);
    }
}
