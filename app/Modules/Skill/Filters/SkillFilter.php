<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class SkillFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return SkillFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('skills.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return SkillFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('skills.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return SkillFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('skills.status', $status);
    }
}
