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
 * Парсинг курсов Eduson Academy.
 */
class ParserEdusonAcademy extends ParserNetology
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::EDUSON_ACADEMY;
    }
}
