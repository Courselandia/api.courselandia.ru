<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class ProcessFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return ProcessFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('processes.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return ProcessFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('processes.name', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return ProcessFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('processes.status', $status);
    }
}
