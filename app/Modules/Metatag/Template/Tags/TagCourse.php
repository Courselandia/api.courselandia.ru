<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template\Tags;

use Log;
use Throwable;
use Config;
use App\Modules\Metatag\Template\Tag;
use Morpher\Ws3Client\Morpher;

/**
 * Тэг обработчик для названия курса: {course}.
 */
class TagCourse extends TagSchool
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'course';
    }
}
