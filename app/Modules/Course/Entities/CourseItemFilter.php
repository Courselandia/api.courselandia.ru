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
 * Сущность для пункта фильтра.
 */
class CourseItemFilter extends Entity
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
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $count = null;

    /**
     * Фильтр отключен.
     *
     * @var bool|null
     */
    public ?bool $disabled = null;
}
