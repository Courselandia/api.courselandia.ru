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
 * Сущность фильтров курсов для цены.
 */
class CourseFilterPrice extends Entity
{
    /**
     * Минимальная цена.
     *
     * @var int|null
     */
    public ?int $min = null;

    /**
     * Максимальная цена.
     *
     * @var int|null
     */
    public ?int $max = null;
}
