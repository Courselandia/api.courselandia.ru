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
 * Парсинг курсов Слёрм.
 */
class ParserSlerm extends ParserNetology
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::SLERM;
    }
}
