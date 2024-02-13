<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Data;

use App\Models\Data;

/**
 * Данные социальных сетей учителя.
 */
class TeacherSocialMedia extends Data
{
    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    public int|string|null $teacher_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Значение.
     *
     * @var string|null
     */
    public ?string $value = null;

    /**
     * @param int|string|null $teacher_id ID учителя.
     * @param string|null $name Название.
     * @param string|null $value Значение.
     */
    public function __construct(
        int|string|null $teacher_id = null,
        ?string         $name = null,
        ?string         $value = null,
    )
    {
        $this->teacher_id = $teacher_id;
        $this->name = $name;
        $this->value = $value;
    }
}
