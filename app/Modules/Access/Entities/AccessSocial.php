<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\Entity;
use App\Modules\User\Entities\User as UserEntity;

/**
 * Сущность для авторизации через социальные сети
 */
class AccessSocial extends Entity
{
    /**
     * ID пользователя.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $first_name = null;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public ?string $second_name = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone = null;

    /**
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verified = false;

    /**
     * Уникальный индикационный номер для авторизации через соц сети.
     *
     * @var string|null
     */
    public ?string $uid = null;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool
     */
    public bool $two_factor = false;

    /**
     * Определяет, произошло ли создание пользователя.
     *
     * @var bool
     */
    public bool $create = false;

    /**
     * Название социальной сети.
     *
     * @var string|null
     */
    public ?string $social = null;

    /**
     * Пользователь со всеми его правами.
     *
     * @var UserEntity|null
     */
    public ?UserEntity $user = null;

    /**
     * Сущность для хранения API токена.
     *
     * @var AccessApiToken|null
     */
    public ?AccessApiToken $token = null;
}
