<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Data\Decorators;

use App\Models\Data;
use App\Modules\OAuth\Values\Token;
use App\Modules\User\Entities\User;

/**
 * Данные для декоратора авторизации.
 */
class AccessSignIn extends Data
{
    /**
     * ID пользователя.
     *
     * @var int|null
     */
    public int|null $id;

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
     * Сущность пользователя.
     *
     * @var User|null
     */
    public ?User $user = null;

    /**
     * @var Token|null
     */
    public ?Token $token = null;

    /**
     * Сущность для хранения API токена.
     *
     * @var bool
     */
    public bool $remember = false;

    /**
     * @param int|null $id ID пользователя.
     * @param string $login Логин.
     * @param ?User $user Сущность пользователя.
     * @param ?Token $token Сущность для хранения API токена.
     * @param bool $remember Запомнить пользователя.
     */
    public function __construct(
        ?int   $id,
        string $login,
        string $password,
        ?User  $user = null,
        ?Token $token = null,
        bool   $remember = false,
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->user = $user;
        $this->token = $token;
        $this->remember = $remember;
    }
}
