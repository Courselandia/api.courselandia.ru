<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Data;

use App\Models\Data;

/**
 * Данные для создания фильтра коллекции.
 */
class CollectionFilterCreate extends Data
{
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
     * @param string|null $name Название.
     * @param string|null $value Значение.
     */
    public function __construct(
        ?string $name = null,
        ?string $value = null,
    )
    {
        $this->name = $name;
        $this->value = $value;
    }
}
