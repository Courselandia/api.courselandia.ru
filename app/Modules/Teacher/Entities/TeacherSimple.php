<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Entities;

use App\Models\Entity;
use App\Modules\Image\Entities\Image;

/**
 * Сущность для учителя - упрощенный вариант.
 */
class TeacherSimple extends Entity
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
     * Изображение маленькое.
     *
     * @var ?Image
     */
    public ?Image $image_small_id = null;

    /**
     * Изображение среднее.
     *
     * @var ?Image
     */
    public ?Image $image_middle_id = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $name Название.
     * @param Image|null $image_small_id Изображение маленькое.
     * @param Image|null $image_middle_id Изображение среднее.
     * @param bool|null $status Статус.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $name = null,
        ?Image          $image_small_id = null,
        ?Image          $image_middle_id = null,
        ?bool           $status = null,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->image_small_id = $image_small_id;
        $this->image_middle_id = $image_middle_id;
        $this->status = $status;
    }
}
