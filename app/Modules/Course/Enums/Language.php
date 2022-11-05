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
 * Валюта.
 */
enum Language: string implements EnumLabel
{
    /**
     * Русский.
     */
    case RU = 'ru';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::RU => 'Русский',
        };
    }
}
