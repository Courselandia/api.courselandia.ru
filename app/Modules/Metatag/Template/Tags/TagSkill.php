<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template\Tags;

/**
 * Тэг обработчик для навыка: {skill}.
 */
class TagSkill extends TagCourse
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'skill';
    }
}
