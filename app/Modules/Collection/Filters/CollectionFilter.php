<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы коллекций.
 */
class CollectionFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return CollectionFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('collections.id', $id);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return CollectionFilter Правила поиска.
     */
    public function name(string $query): self
    {
        return $this->whereLike('collections.name', $query);
    }

    /**
     * Поиск по направлению.
     *
     * @param string|int|array<string|int> $ids Направления.
     *
     * @return CollectionFilter Правила поиска.
     */
    public function directionsId(string|int|array $ids): self
    {
        $ids = is_array($ids) ? $ids : [$ids];

        return $this->whereIn('collections.direction_id', $ids);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return CollectionFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('collections.status', $status);
    }
}
