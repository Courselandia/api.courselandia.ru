<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Enums;

use App\Models\Enums\EnumLabel;

enum Role: string implements EnumLabel
{
    /**
     * Администратор.
     */
    case ADMIN = 'admin';

    /**
     * Менеджер.
     */
    case MANAGER = 'manager';

    /**
     * Пользователь.
     */
    case USER = 'user';

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::ADMIN => 'Администратор',
            self::MANAGER => 'Менеджер',
            self::USER => 'Пользователь',
        };
    }
}