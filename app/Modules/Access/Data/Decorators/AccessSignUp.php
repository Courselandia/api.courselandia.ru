<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Data\Decorators;

use App\Models\Data;
use App\Modules\Access\Entities\AccessApiToken;
use App\Modules\User\Entities\User;

/**
 * Данные для декоратора регистрации.
 */
class AccessSignUp extends Data
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
     *
     * Пароль.
     * @var string|null
     */
    public string|null $password;

    /**
     * Имя.
     *
     * @var string|null
     */
    public string|null $first_name = null;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public string|null $second_name = null;

    /**
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verified = false;

    /**
     * Признак того нужно ли создавать нового пользователя.
     *
     * @var bool
     */
    public bool $create = true;

    /**
     * Сущность пользователя.
     *
     * @var User|null
     */
    public ?User $user = null;

    /**
     * Сущность для хранения API токена.
     *
     * @var AccessApiToken|null
     */
    public ?AccessApiToken $token = null;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool
     */
    public bool $two_factor = false;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember = false;

    /**
     * @param int|null $id ID пользователя.
     * @param string $login Логин.
     * @param string|null $password Пароль.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param bool $verified Статус верификации.
     * @params bool $create Признак, что пользователя нужно создать.
     * @param ?User $user Сущность пользователя.
     * @param ?AccessApiToken $token Сущность для хранения API токена.
     * @param bool $two_factor Двухфакторная аутентификация.
     * @param bool $remember Запомнить пользователя.
     */
    public function __construct(
        ?int            $id,
        string          $login,
        ?string         $password,
        ?string         $first_name,
        ?string         $second_name,
        bool            $verified = false,
        bool            $create = true,
        bool            $two_factor = false,
        bool            $remember = false,
        ?User           $user = null,
        ?AccessApiToken $token = null,
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->user = $user;
        $this->token = $token;
        $this->verified = $verified;
        $this->create = $create;
        $this->two_factor = $two_factor;
        $this->remember = $remember;
    }
}
