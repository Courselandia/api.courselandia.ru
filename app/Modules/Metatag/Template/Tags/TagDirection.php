<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template\Tags;

/**
 * Тэг обработчик для направления курса: {direction}.
 */
class TagDirection extends TagSchool
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'direction';
    }
}
