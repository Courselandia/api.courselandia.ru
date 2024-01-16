<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\DTO\Actions;

use App\Models\DTO;

/**
 * DTO для действия авторизации.
 */
class AccessSignIn extends DTO
{
    /**
     * Логин.
     *
     * @var string
     */
    public string $login;

    /**
     * Пароль.
     *
     * @var string
     */
    public string $password;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember;

    /**
     * @param string $login Логин.
     * @param string $password Пароль.
     * @param bool $remember Запомнить пользователя.
     */
    public function __construct(
        string $login,
        string $password,
        bool   $remember = false,
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->remember = $remember;
    }
}
