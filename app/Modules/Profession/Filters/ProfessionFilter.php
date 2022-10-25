<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class ProfessionFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int $id ID.
     *
     * @return ProfessionFilter Правила валидации.
     */
    public function id(int $id): ProfessionFilter
    {
        return $this->where('professions.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return ProfessionFilter Правила валидации.
     */
    public function name(string $query): ProfessionFilter
    {
        return $this->whereLike('professions.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return ProfessionFilter Правила валидации.
     */
    public function status(bool $status): ProfessionFilter
    {
        return $this->where('professions.status', $status);
    }
}
