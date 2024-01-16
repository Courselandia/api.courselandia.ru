<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\EntityNew;
use App\Modules\User\Entities\User as UserEntity;

/**
 * Сущность для зарегистрированного пользователя.
 */
class AccessSignedUp extends EntityNew
{
    /**
     * Сущность пользователя.
     *
     * @var string
     */
    public string $accessToken;

    /**
     * Токен доступа.
     *
     * @var string
     */
    public string $refreshToken;

    /**
     * Токен обновления.
     *
     * @var UserEntity
     */
    public UserEntity $user;

    /**
     * @param UserEntity $user Сущность пользователя.
     * @param string $accessToken Токен доступа.
     * @param string $refreshToken Токен обновления.
     */
    public function __construct(UserEntity $user, string $accessToken, string $refreshToken)
    {
        $this->user = $user;
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }
}
