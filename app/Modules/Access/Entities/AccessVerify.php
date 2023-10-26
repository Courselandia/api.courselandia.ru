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
 * Сущность для валидации пользователя.
 */
class AccessVerify extends Entity
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * Код верификации.
     *
     * @var string
     */
    public string $code;

    /**
     * Пользователь со всеми его правами.
     *
     * @var UserEntity|null
     */
    public ?UserEntity $user;

    /**
     * Сущность для хранения API токена.
     *
     * @var AccessApiToken|null
     */
    public ?AccessApiToken $token;
}
