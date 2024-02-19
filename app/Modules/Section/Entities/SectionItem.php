<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Entities;

use App\Models\Entity;

/**
 * Модель элемента раздела.
 */
class SectionItem extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID раздела.
     *
     * @var int|string|null
     */
    public int|string|null $section_id = null;

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
     * Значение модели этого элемента.
     *
     * @var array|null
     */
    public array|null $itemable = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $section_id ID раздела.
     * @param int|null $weight Вес элемента.
     * @param int|string|null $itemable_id ID сущности для элемента.
     * @param string|null $itemable_type Имя класса сущности для элемента.
     * @param array|null $itemable Значение модели этого элемента.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $section_id = null,
        ?int            $weight = null,
        int|string|null $itemable_id = null,
        string|null     $itemable_type = null,
        array|null      $itemable = null,
    )
    {
        $this->id = $id;
        $this->section_id = $section_id;
        $this->weight = $weight;
        $this->itemable_id = $itemable_id;
        $this->itemable_type = $itemable_type;
        $this->itemable = $itemable;
    }
}


