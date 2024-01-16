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
 * DTO для действия генерации токена.
 */
class AccessApiToken extends DTO
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
     * @var string|null
     */
    public string|null $password;

    /**
     * Пропустить проверку пароля пользователя.
     *
     * @var bool
     */
    public bool $force;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember;

    /**
     * @param string $login Логин.
     * @param string|null $password Пароль.
     * @param bool $force Пропустить проверку пароля пользователя.
     * @param bool $remember Запомнить пользователя.
     */
    public function __construct(
        string $login,
        ?string $password,
        bool $force = false,
        bool $remember = false
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->force = $force;
        $this->remember = $remember;
    }
}
