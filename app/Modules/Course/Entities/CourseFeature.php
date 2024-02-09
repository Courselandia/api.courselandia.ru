<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;

/**
 * Сущность для особенностей курсов.
 */
class CourseFeature extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID курса.
     *
     * @var int|string|null
     */
    public int|string|null $course_id = null;

    /**
     * Текст.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Иконка.
     *
     * @var string|null
     */
    public ?string $icon = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $course_id ID курса.
     * @param string|null $text Текст.
     * @param string|null $icon Иконка.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $course_id = null,
        ?string         $text = null,
        ?string         $icon = null
    )
    {
        $this->id = $id;
        $this->course_id = $course_id;
        $this->text = $text;
        $this->icon = $icon;
    }
}
