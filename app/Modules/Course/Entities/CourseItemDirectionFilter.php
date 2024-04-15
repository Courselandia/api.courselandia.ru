<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

/**
 * Сущность для пункта фильтра.
 */
class CourseItemDirectionFilter extends CourseItemFilter
{
    /**
     * Категории.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $categories = null;

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $name Название.
     * @param string|null $link Ссылка.
     * @param int|null $count Количество.
     * @param bool|null $disabled Фильтр отключен.
     * @param array<int, CourseItemFilter>|null $categories Категории.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $name = null,
        ?string         $link = null,
        ?int            $count = null,
        ?bool           $disabled = null,
        ?array          $categories = null,
    )
    {
        $this->categories = $categories;

        parent::__construct($id, $name, $link, $count, $disabled);
    }
}
