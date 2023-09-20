<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;
use App\Modules\Course\Entities\Course as CourseEntity;

/**
 * Сущность получения курса.
 */
class CourseGet extends Entity
{
    /**
     * Курс.
     *
     * @var CourseEntity|null
     */
    public ?CourseEntity $course = null;

    /**
     * Похожие курсы.
     *
     * @var Course[]
     */
    public ?array $similarities = null;

    /**
     * Ссылка школы.
     *
     * @var string|null
     */
    public string|null $school = null;

    /**
     * Ссылка курса.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * ID курса.
     *
     * @var string|int|null
     */
    public string|null $id = null;
}
