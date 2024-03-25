<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Entities;

use Carbon\Carbon;
use App\Models\Entity;

/**
 * Сущность для фильтров коллекции.
 */
class CollectionFilter extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID коллекции.
     *
     * @var int|string|null
     */
    public int|string|null $collection_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Значение.
     *
     * @var string|null
     */
    public ?string $value = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;

    /**
     * Коллекция.
     *
     * @var ?Collection
     */
    public ?Collection $collection = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $collection_id ID коллекции.
     * @param string|null $name Название.
     * @param string|null $value Значение.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param Collection|null $collection Коллекция.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $collection_id = null,
        ?string         $name = null,
        ?string         $value = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?Collection     $collection = null,
    )
    {
        $this->id = $id;
        $this->collection_id = $collection_id;
        $this->name = $name;
        $this->value = $value;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->collection = $collection;
    }
}
