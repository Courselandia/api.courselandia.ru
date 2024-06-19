<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Imports\Parsers;

use App\Modules\School\Enums\School;

/**
 * Парсинг курсов Компьютерная Академия TOP.
 */
class ParserTopAcademy extends ParserNetology
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::TOP_ACADEMY;
    }
}
