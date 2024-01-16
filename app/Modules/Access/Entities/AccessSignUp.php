<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\EntityNew;

/**
 * Сущность для регистрации пользователя.
 */
class AccessSignUp extends EntityNew
{
    /**
     * ID пользователя.
     *
     * @var string|int
     */
    public string|int $id;

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
     * Уникальный индикационный номер для авторизации через соц сети.
     *
     * @var string|null
     */
    public string|null $uid;

    /**
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verified;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool
     */
    public bool $two_factor;

    /**
     * Создать пользователя.
     *
     * @var bool
     */
    public bool $create;

    /**
     * @param string|int $id ID пользователя.
     * @param string $login Логин.
     * @param string $password Пароль.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param string|null $uid Уникальный индикационный номер для авторизации через соц сети.
     * @param bool $verified Статус верификации.
     * @param bool $two_factor Двухфакторная аутентификация.
     * @param bool $create Создать пользователя.
     */
    public function __construct(
        string|int $id,
        string     $login,
        string     $password,
        ?string    $first_name,
        ?string    $second_name,
        ?string    $phone,
        ?string    $uid,
        bool       $verified = false,
        bool       $two_factor = false,
        bool       $create = true,
    )
    {
        $this->id = $id;
        $this->login = $login;
        $this->password = $password;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->uid = $uid;
        $this->verified = $verified;
        $this->two_factor = $two_factor;
        $this->create = $create;
    }
}
