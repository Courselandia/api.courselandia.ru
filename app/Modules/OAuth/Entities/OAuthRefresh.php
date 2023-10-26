<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Entities;

use Carbon\Carbon;

/**
 * Сущность для токена обновления.
 */
class OAuthRefresh extends Token
{
    /**
     * ID токена на обновления.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * ID токена.
     *
     * @var string|int|null
     */
    public string|int|null $oauth_token_id = null;

    /**
     * Токен обновления.
     *
     * @var string|null
     */
    public ?string $refresh_token = null;

    /**
     * Дата истечения.
     *
     * @var Carbon|null
     */
    public ?Carbon $expires_at = null;
}
