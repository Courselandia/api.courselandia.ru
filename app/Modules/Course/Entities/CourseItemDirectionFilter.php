<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entities;

/**
 * Сущность для пункта фильтра.
 */
class CourseItemDirectionFilter extends CourseItemFilter
{
    /**
     * Категории.
     *
     * @var CourseItemFilter[]
     */
    #[Entities(CourseItemFilter::class)]
    public ?array $categories = null;
}
