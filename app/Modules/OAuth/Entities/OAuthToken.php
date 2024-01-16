<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Entities;

use App\Models\EntityNew;
use Carbon\Carbon;

/**
 * Сущность для токена.
 */
class OAuthToken extends EntityNew
{
    /**
     * ID токена на обновления.
     *
     * @var string|int|null
     */
    public string|int|null $id;

    /**
     * ID пользователя.
     *
     * @var string|int
     */
    public string|int $user_id;

    /**
     * Токен.
     *
     * @var string
     */
    public string $token;

    /**
     * Дата истечения.
     *
     * @var Carbon
     */
    public Carbon $expires_at;

    /**
     * @param string|int|null $id ID токена на обновления.
     * @param string|int $user_id ID пользователя.
     * @param string $token Токен.
     * @param Carbon $expires_at Дата истечения.
     */
    public function __construct(
        string|int|null $id,
        string|int $user_id,
        string $token,
        Carbon $expires_at,
    )
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->token = $token;
        $this->expires_at = $expires_at;
    }
}
