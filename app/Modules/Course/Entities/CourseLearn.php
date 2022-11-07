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
 * Сущность чему научитесь на курсе.
 */
class CourseLearn extends Entity
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
}
