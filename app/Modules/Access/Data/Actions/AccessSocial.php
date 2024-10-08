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
 * Данные для действия регистрации или входа через социальную сеть.
 */
class AccessSocial extends Data
{
    /**
     * Логин.
     *
     * @var string
     */
    public string $login;

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
    public bool $verified;

    /**
     * @param string $login Логин.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string $uid Индификатор социальной сети.
     * @param string $social Социальная сеть.
     * @param bool $verified Статус верификации.
     */
    public function __construct(
        string  $login,
        string  $uid,
        string  $social,
        ?string $first_name = null,
        ?string $second_name = null,
        bool    $verified = false,
    )
    {
        $this->login = $login;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->uid = $uid;
        $this->social = $social;
        $this->verified = $verified;
    }
}
