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
 * Данные для создания значения виджета.
 */
class WidgetValue extends Data
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
     * @var array|string|int|null
     */
    public array|string|int|null $value = null;

    /**
     * @param string|null $name Название.
     * @param array|string|int|null $value Значение.
     */
    public function __construct(
        ?string $name = null,
        array|string|int|null $value = null,
    ) {
        $this->name = $name;
        $this->value = $value;
    }
}
