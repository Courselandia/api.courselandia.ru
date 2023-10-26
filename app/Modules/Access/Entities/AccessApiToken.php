<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Modules\User\Entities\User;

/**
 * Сущность для хранения API токена.
 */
class AccessApiToken
{
    /**
     * Токен доступа.
     *
     * @var string|null
     */
    public ?string $accessToken = null;

    /**
     * Токен обновления.
     *
     * @var string|null
     */
    public ?string $refreshToken = null;

    /**
     * Сущность пользователя.
     *
     * @var User
     */
    public User $user;
}
