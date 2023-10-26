<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\Entity;
use App\Modules\OAuth\Entities\Token;
use App\Modules\User\Entities\User;

/**
 * Сущность для авторизации пользователя.
 */
class AccessSignIn extends Entity
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * Логин пользователя.
     *
     * @var string
     */
    public string $login;

    /**
     * Пароль пользователя.
     *
     * @var string
     */
    public string $password;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember = false;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool
     */
    public bool $two_factor = false;

    /**
     * Токена.
     *
     * @var Token|null
     */
    public ?Token $token;

    /**
     * Сущность пользователя.
     *
     * @var User
     */
    public User $user;
}
