<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template\Tags;

/**
 * Тэг обработчик для категории курса: {category}.
 */
class TagCategory extends TagSchool
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'category';
    }
}
