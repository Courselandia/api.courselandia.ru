<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Data;

use App\Models\Data;

/**
 * Данные для создания элемента раздела.
 */
class SectionItem extends Data
{
    /**
     * Вес элемента.
     *
     * @var int|null
     */
    public ?int $weight = null;

    /**
     * ID сущности для элемента.
     *
     * @var int|string|null
     */
    public int|string|null $itemable_id = null;

    /**
     * Имя класса сущности для элемента.
     *
     * @var string|null
     */
    public string|null $itemable_type = null;

    /**
     * @param int|null $weight Вес элемента.
     * @param int|string|null $itemable_id ID сущности для элемента.
     * @param string|null $itemable_type Имя класса сущности для элемента.
     */
    public function __construct(
        ?int            $weight = null,
        int|string|null $itemable_id = null,
        string|null     $itemable_type = null,
    )
    {
        $this->weight = $weight;
        $this->itemable_id = $itemable_id;
        $this->itemable_type = $itemable_type;
    }
}
