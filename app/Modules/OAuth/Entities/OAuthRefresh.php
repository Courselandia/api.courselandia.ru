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
 * Сущность для токена обновления.
 */
class OAuthRefresh extends EntityNew
{
    /**
     * ID токена на обновления.
     *
     * @var string|int|null
     */
    public string|int|null $id;

    /**
     * ID токена.
     *
     * @var string|int
     */
    public string|int $oauth_token_id;

    /**
     * Токен обновления.
     *
     * @var string
     */
    public string $refresh_token;

    /**
     * Дата истечения.
     *
     * @var Carbon
     */
    public Carbon $expires_at;

    /**
     * @param string|int|null $id ID токена на обновления.
     * @param string|int $oauth_token_id ID токена.
     * @param string $refresh_token Токен обновления.
     * @param Carbon $expires_at Дата истечения.
     */
    public function __construct(
        string|int|null $id,
        string|int      $oauth_token_id,
        string          $refresh_token,
        Carbon          $expires_at
    )
    {
        $this->id = $id;
        $this->oauth_token_id = $oauth_token_id;
        $this->refresh_token = $refresh_token;
        $this->expires_at = $expires_at;
    }
}
