<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template\Tags;

/**
 * Тэг обработчик для количества курсов школы: {countSchoolCourses}.
 */
class TagCountSchoolCourses extends TagCountDirectionCourses
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'countSchoolCourses';
    }
}
