<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Enums;

use App\Models\Enums\EnumLabel;

/**
 * Продолжительность.
 */
enum Duration: string implements EnumLabel
{
    /**
     * День.
     */
    case DAY = 'day';

    /**
     * Неделя.
     */
    case WEEK = 'week';

    /**
     * Месяц.
     */
    case MONTH = 'month';

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
            self::DAY => 'День',
            self::WEEK => 'Неделя',
            self::MONTH => 'Месяц',
            self::YEAR => 'Год',
        };
    }
}
