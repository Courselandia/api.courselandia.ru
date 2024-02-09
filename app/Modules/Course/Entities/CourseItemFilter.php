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

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $name Название.
     * @param string|null $link Ссылка.
     * @param int|null $count Количество.
     * @param bool|null $disabled Фильтр отключен.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $name = null,
        ?string         $link = null,
        ?int            $count = null,
        ?bool           $disabled = null,
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
        $this->count = $count;
        $this->disabled = $disabled;
    }
}
