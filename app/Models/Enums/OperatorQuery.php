<?php
/**
 * Перечисления.
 * Этот пакет содержит перечисления для ядра системы.
 *
 * @package App.Models.Enums
 */

namespace App\Models\Enums;

/**
 * Оператор сравнения для запроса.
 */
enum OperatorQuery: string
{
    /**
     * Ровно.
     */
    case EQUAL = '=';

    /**
     * Не равно.
     */
    case NOT_EQUAL = '!=';

    /**
     * Больше.
     */
    case GT = '>';

    /**
     * Больше или равно.
     */
    case GTE = '>=';

    /**
     * Меньше.
     */
    case LT = '<';

    /**
     * Меньше или равное.
     */
    case LTE = '<=';

    /**
     * IN.
     */
    case IN = 'IN';

    /**
     * NOT IN.
     */
    case NOT_IN = 'NOT IN';
}