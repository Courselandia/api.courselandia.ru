<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\Entity;
use App\Modules\User\Entities\User as UserEntity;

/**
 * Сущность для зарегистрированного пользователя.
 */
class AccessSignedUp extends Entity
{
    /**
     * Пользователь со всеми его правами.
     *
     * @var UserEntity|null
     */
    public ?UserEntity $user = null;

    /**
     * Секретный ключ.
     *
     * @var string|null
     */
    public ?string $secret = null;

    /**
     * Токен авторизации.
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
}