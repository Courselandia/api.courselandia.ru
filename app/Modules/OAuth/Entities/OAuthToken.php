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
class OAuthToken extends Token
{
    /**
     * ID токена на обновления.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * ID пользователя.
     *
     * @var string|int|null
     */
    public string|int|null $user_id = null;

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
