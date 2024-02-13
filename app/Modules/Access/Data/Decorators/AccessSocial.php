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
 * Данные для декоратора регистрации или входа через социальную сеть.
 */
class AccessSocial extends Data
{
    /**
     * ID пользователя.
     *
     * @var int|null
     */
    public int|null $id = null;

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
    public string|null $password = null;

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
     * Индификатор социальной сети.
     *
     * @var string
     */
    public string $uid;

    /**
     * Социальная сеть.
     *
     * @var string
     */
    public string $social;

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
        string          $login,
        string          $uid,
        string          $social,
        ?int            $id = null,
        ?string         $password = null,
        ?string         $first_name = null,
        ?string         $second_name = null,
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
