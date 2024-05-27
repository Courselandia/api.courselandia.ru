<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Enums;

/**
 * Тип промокода.
 */
enum Type: string
{
    /**
     * Скидка.
     */
    case DISCOUNT = 'discount';

    /**
     * Подарок.
     */
    case PRESENT = 'present';
}
