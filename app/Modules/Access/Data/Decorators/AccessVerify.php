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
 * Данные для декоратора верификации пользователя.
 */
class AccessVerify extends Data
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
     * @param int|string $id ID пользователя.
     * @param string $code Код верификации.
     * @param ?User $user Сущность пользователя.
     * @param ?AccessApiToken $token Сущность для хранения API токена.
     */
    public function __construct(
        int|string      $id,
        string          $code,
        ?User           $user = null,
        ?AccessApiToken $token = null,
    )
    {
        $this->id = $id;
        $this->code = $code;
        $this->user = $user;
        $this->token = $token;
    }
}
