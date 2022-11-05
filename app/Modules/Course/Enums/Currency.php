<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Enums;

/**
 * Валюта.
 */
enum Currency: string
{
    /**
     * Рубли.
     */
    case RUB = 'RUB';

    /**
     * Доллар.
     */
    case USD = 'USD';

    /**
     * Евро.
     */
    case EUR = 'EUR';
}
