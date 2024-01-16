<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Data\Actions;

use App\Models\Data;

/**
 * Данные для действия генерации токена.
 */
class AccessApiToken extends Data
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
    public bool $force = false;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember = false;

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
