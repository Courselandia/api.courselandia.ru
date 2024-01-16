<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\EntityNew;
use App\Modules\OAuth\Values\Token;
use App\Modules\User\Entities\User;

/**
 * Сущность для авторизации пользователя.
 */
class AccessSignIn extends EntityNew
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
     * Токена.
     *
     * @var Token
     */
    public Token $token;

    /**
     * Сущность пользователя.
     *
     * @var User
     */
    public User $user;

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
     * @param int|string $id ID пользователя.
     * @param string $login Логин пользователя.
     * @param string $password Пароль пользователя.
     * @param Token $token Токена.
     * @param User $user Сущность пользователя.
     * @param bool $remember Запомнить пользователя.
     * @param bool $two_factor Двухфакторная аутентификация.
     */
    public function __construct(
        int|string $id,
        string     $login,
        string     $password,
        Token      $token,
        User       $user,
        bool       $remember = false,
        bool       $two_factor = false,
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->token = $token;
        $this->user = $user;
        $this->remember = $remember;
        $this->two_factor = $two_factor;
    }
}
