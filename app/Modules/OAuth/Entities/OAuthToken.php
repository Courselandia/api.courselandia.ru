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
 * Сущность для токена.
 */
class OAuthToken extends TokenPair
{
    /**
     * ID токена на обновления.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * ID клиента.
     *
     * @var string|int|null
     */
    public string|int|null $oauth_client_id = null;

    /**
     * Токен.
     *
     * @var ?string
     */
    public ?string $token = null;

    /**
     * Дата истечения.
     *
     * @var ?Carbon
     */
    public ?Carbon $expires_at = null;
}
