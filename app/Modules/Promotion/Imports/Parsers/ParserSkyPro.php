<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Imports\Parsers;

use App\Modules\School\Enums\School;

/**
 * Парсинг курсов SkyPro.
 */
class ParserSkyPro extends ParserNetology
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::SKYPRO;
    }
}
