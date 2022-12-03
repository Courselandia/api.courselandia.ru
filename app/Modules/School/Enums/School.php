<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Enums;

use App\Models\Enums\EnumLabel;

enum School: int implements EnumLabel
{
    /**
     * Нетология.
     */
    case NETOLOGIA = 1;

    /**
     * Skillbox.
     */
    case SKILLBOX = 2;

    /**
     * GeekBrains.
     */
    case GEEKBRAINS = 3;

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::NETOLOGIA => 'Нетология',
            self::SKILLBOX => 'Skillbox',
            self::GEEKBRAINS => 'GeekBrains',
        };
    }
}
