<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;
use App\Modules\Salary\Enums\Level;

/**
 * Сущность уровней курсов.
 */
class CourseLevel extends Entity
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
     * Уровень.
     *
     * @var Level|null
     */
    public ?Level $level = null;
}
