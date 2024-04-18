<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы терминов.
 */
class TermFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return TermFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('terms.id', $id);
    }

    /**
     * Поиск по варианту термина.
     *
     * @param string $query Строка поиска.
     *
     * @return TermFilter Правила поиска.
     */
    public function variant(string $query): self
    {
        return $this->whereLike('terms.variant', $query);
    }

    /**
     * Поиск по термину.
     *
     * @param string $query Строка поиска.
     *
     * @return TermFilter Правила поиска.
     */
    public function term(string $query): self
    {
        return $this->whereLike('terms.term', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return TermFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('skills.status', $status);
    }
}
