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
 * Сущность фильтров курсов для рейтингов.
 */
class CourseFilterRating extends Entity
{
    /**
     * Название.
     *
     * @var string|int|null
     */
    public string|int|null $label = null;

    /**
     * Признак включенности.
     *
     * @var bool|null
     */
    public ?bool $disabled = null;

    /**
     * @param string|int|null $label Название.
     * @param bool|null $disabled Признак включенности.
     */
    public function __construct(
        string|int|null $label = null,
        ?bool $disabled = null,
    ) {
        $this->label = $label;
        $this->disabled = $disabled;
    }
}
