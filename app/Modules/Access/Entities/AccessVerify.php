<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\Access\Entities;

use App\Models\EntityNew;
use App\Modules\User\Entities\User as UserEntity;

/**
 * Сущность для валидации пользователя.
 */
class AccessVerify extends EntityNew
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
     * @var UserEntity
     */
    public UserEntity $user;

    /**
     * Сущность для хранения API токена.
     *
     * @var AccessApiToken
     */
    public AccessApiToken $token;

    /**
     * @param int|string $id ID пользователя.
     * @param string $code Код верификации.
     * @param UserEntity $user Пользователь со всеми его правами.
     * @param AccessApiToken $token Сущность для хранения API токена.
     */
    public function __construct(
        int|string     $id,
        string         $code,
        UserEntity     $user,
        AccessApiToken $token
    )
    {
        $this->id = $id;
        $this->code = $code;
        $this->user = $user;
        $this->token = $token;
    }
}
