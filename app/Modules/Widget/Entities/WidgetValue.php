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
 * Сущность для значения виджета.
 */
class WidgetValue extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID виджета.
     *
     * @var int|string|null
     */
    public int|string|null $widget_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Значение.
     *
     * @var array|null
     */
    public ?array $value = null;

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
     * Виджет.
     *
     * @var ?Widget
     */
    public ?Widget $widget = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $widget_id ID виджета.
     * @param string|null $name Название.
     * @param array|null $value Значение.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param Widget|null $widget Виджет.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $widget_id = null,
        ?string $name = null,
        ?array $value = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null,
        ?Widget $widget = null
    ) {
        $this->id = $id;
        $this->widget_id = $widget_id;
        $this->name = $name;
        $this->value = $value;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->widget = $widget;
    }
}
