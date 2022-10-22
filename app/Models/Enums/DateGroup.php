<?php
/**
 * Перечисления.
 * Этот пакет содержит перечисления для ядра системы.
 *
 * @package App.Models.Enums
 */

namespace App\Models\Enums;

/**
 * Группировка для дат.
 */
enum DateGroup: string implements EnumLabel
{
    /**
     * По дням.
     */
    case DAY = 'day';

    /**
     * По неделям.
     */
    case WEEK = 'week';

    /**
     * По месяцам.
     */
    case MONTH = 'month';

    /**
     * По годам.
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
            self::DAY => 'По дням',
            self::WEEK => 'По неделям',
            self::MONTH => 'По месяцам',
            self::YEAR => 'По годам',
        };
    }
}