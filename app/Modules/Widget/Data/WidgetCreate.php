<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Data;

use App\Models\Data;

/**
 * Данные для создания виджета.
 */
class WidgetCreate extends Data
{
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
     * Значения.
     *
     * @var ?array<int, WidgetValue>
     */
    public ?array $values = null;

    /**
     * @param string|null $name Название.
     * @param string|null $index Индекс.
     * @param bool|null $status Статус.
     * @param array<int, WidgetValue>|null $values Значения.
     */
    public function __construct(
        ?string $name = null,
        ?string $index = null,
        ?bool $status = null,
        ?array $values = null,
    ) {
        $this->name = $name;
        $this->index = $index;
        $this->status = $status;
        $this->values = $values;
    }
}
