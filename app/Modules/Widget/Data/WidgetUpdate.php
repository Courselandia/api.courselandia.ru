<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Data;

/**
 * Данные для обновления виджета.
 */
class WidgetUpdate extends WidgetCreate
{
    /**
     * ID навыка.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID навыка.
     * @param string|null $name Название.
     * @param string|null $index Индекс.
     * @param bool|null $status Статус.
     * @param array<int, WidgetValue>|null $values Значения.
     */
    public function __construct(
        int|string $id,
        ?string $name = null,
        ?string $index = null,
        ?bool $status = null,
        ?array $values = null,
    ) {
        $this->id = $id;

        parent::__construct($name, $index, $status, $values);
    }
}
