<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Enums;

use App\Models\Enums\EnumLabel;

enum Level: string implements EnumLabel
{
    /**
     * Начинающий.
     */
    case JUNIOR = 'junior';

    /**
     * Средний.
     */
    case MIDDLE = 'middle';

    /**
     * Профессионал.
     */
    case SENIOR = 'senior';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::JUNIOR => 'Начинающий',
            self::MIDDLE => 'Средний',
            self::SENIOR => 'Профессионал',
        };
    }
}
