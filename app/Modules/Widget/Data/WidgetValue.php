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
     * Индекс.
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
    ) {
        $this->name = $name;
        $this->value = $value;
    }
}
