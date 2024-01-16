<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\DTO\Decorators;

use App\Models\DTO;
use App\Modules\Access\Entities\AccessApiToken;
use App\Modules\User\Entities\User;

/**
 * DTO для декоратора регистрации или входа через социальную сеть.
 */
class AccessSocial extends DTO
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
     * @var string|null
     */
    public string|null $password;

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
     * Индификатор социальной сети.
     *
     * @var string|null
     */
    public string|null $uid;

    /**
     * Социальная сеть.
     *
     * @var string|null
     */
    public string|null $social;

    /**
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verified;

    /**
     * Признак того нужно ли создавать нового пользователя.
     *
     * @var bool
     */
    public bool $create;

    /**
     * Сущность пользователя.
     *
     * @var User|null
     */
    public ?User $user;

    /**
     * Сущность для хранения API токена.
     *
     * @var AccessApiToken|null
     */
    public ?AccessApiToken $token;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool
     */
    public bool $two_factor;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember;

    /**
     * @param int|null $id ID пользователя.
     * @param string $login Логин.
     * @param string|null $password Пароль.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $uid Индификатор социальной сети.
     * @param string|null $social Социальная сеть.
     * @param bool $verified Статус верификации.
     * @param ?User $user Сущность пользователя.
     * @param ?AccessApiToken $token Сущность для хранения API токена.
     * @params bool $create Признак, что пользователя нужно создать.
     * @param bool $two_factor Двухфакторная аутентификация.
     * @param bool $remember Запомнить пользователя.
     */
    public function __construct(
        ?int            $id,
        string          $login,
        ?string         $password,
        ?string         $first_name,
        ?string         $second_name,
        ?string         $uid,
        ?string         $social,
        ?User           $user,
        ?AccessApiToken $token,
        bool            $verified = false,
        bool            $create = true,
        bool            $two_factor = false,
        bool            $remember = false,
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->uid = $uid;
        $this->social = $social;
        $this->user = $user;
        $this->token = $token;
        $this->verified = $verified;
        $this->create = $create;
        $this->two_factor = $two_factor;
        $this->remember = $remember;
    }
}
