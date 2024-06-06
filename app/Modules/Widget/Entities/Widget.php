<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для виджета.
 */
class Widget extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Индекс.
     *
     * @var string|null
     */
    public ?string $index = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * Значения.
     *
     * @var ?array<string, array<string | int>>
     */
    public ?array $values = null;

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $name Название.
     * @param string|null $index Индекс.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param array<string, array<string | int>>|null $values Значения.
     */
    public function __construct(
        int|string|null $id = null,
        ?string $name = null,
        ?string $index = null,
        ?bool $status = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null,
        ?array $values = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->index = $index;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->values = $values;
    }
}
