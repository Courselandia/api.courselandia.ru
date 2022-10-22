<?php
/**
 * Перечисления.
 * Этот пакет содержит перечисления для ядра системы.
 *
 * @package App.Models.Enums
 */

namespace App\Models\Enums;

/**
 * Период для дат.
 */
enum DatePeriod: string implements EnumLabel
{
    /**
     * Сегодня.
     */
    case TODAY = 'day';

    /**
     * Вчера.
     */
    case YESTERDAY = 'yesterday';

    /**
     * Неделя.
     */
    case WEEK = 'week';

    /**
     * Месяц.
     */
    case MONTH = 'month';

    /**
     * Квартал.
     */
    case QUARTER = 'quarter';

    /**
     * Год.
     */
    case YEAR = 'year';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::TODAY => 'Сегодня',
            self::YESTERDAY => 'Вчера',
            self::WEEK => 'Неделя',
            self::MONTH => 'Месяц',
            self::QUARTER => 'Квартал',
            self::YEAR => 'Год',
        };
    }
}