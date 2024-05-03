<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Enums;

/**
 * Тип скидки.
 */
enum DiscountType: string
{
    /**
     * Проценты.
     */
    case PERCENT = 'percent';

    /**
     * Рубли.
     */
    case RUB = 'rub';
}
