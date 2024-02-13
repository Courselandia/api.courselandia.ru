<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Сущность для пункта фильтра.
 */
class CourseItemDirectionFilter extends CourseItemFilter
{
    /**
     * Категории.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $categories = null;
}
