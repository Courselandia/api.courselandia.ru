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
use Spatie\LaravelData\Optional;

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
     * @var Image|Optional|null
     */
    public Image|Optional|null $image_small_id;

    /**
     * Изображение среднее.
     *
     * @var Image|Optional|null
     */
    public Image|Optional|null $image_middle_id;

    /**
     * Статус.
     *
     * @var bool|Optional|null
     */
    public bool|Optional|null $status;

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $name Название.
     * @param Image|Optional|null $image_small_id Изображение маленькое.
     * @param Image|Optional|null $image_middle_id Изображение среднее.
     * @param bool|Optional|null $status Статус.
     */
    public function __construct(
        bool|Optional|null $status,
        Image|Optional|null $image_small_id,
        Image|Optional|null $image_middle_id,
        int|string|null $id = null,
        ?string $name = null,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->image_small_id = $image_small_id;
        $this->image_middle_id = $image_middle_id;
        $this->status = $status;
    }
}
