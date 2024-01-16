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
 * DTO для действия регистрация нового пользователя.
 */
class AccessSignUp extends DTO
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
     * Имя.
     *
     * @var string|null
     */
    public string|null $first_name;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public string|null $second_name;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public string|null $phone;

    /**
     * Верифицировать пользователя.
     *
     * @var bool
     */
    public bool $verify;

    /**
     * @param string $login Логин.
     * @param string $password Пароль.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param bool $verify Верифицировать пользователя.
     */
    public function __construct(
        string  $login,
        string  $password,
        ?string $first_name,
        ?string $second_name,
        ?string $phone,
        bool    $verify = false,
    )
    {
        $this->login = $login;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->verify = $verify;
    }
}
