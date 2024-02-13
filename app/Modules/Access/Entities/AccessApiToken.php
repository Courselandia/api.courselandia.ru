<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\Entity;
use App\Modules\User\Entities\User;

/**
 * Сущность для хранения API токена.
 */
class AccessApiToken extends Entity
{
    /**
     * Токен доступа.
     *
     * @var string
     */
    public string $accessToken;

    /**
     * Токен обновления.
     *
     * @var string
     */
    public string $refreshToken;

    /**
     * Сущность пользователя.
     *
     * @var User
     */
    public User $user;

    /**
     * @param string $accessToken Токен доступа.
     * @param string $refreshToken Токен обновления.
     * @param User $user Сущность пользователя.
     */
    public function __construct(string $accessToken, string $refreshToken, User $user)
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->user = $user;
    }
}
