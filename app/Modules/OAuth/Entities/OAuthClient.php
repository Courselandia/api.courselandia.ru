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
 * Сущность для клиента.
 */
class OAuthClient extends TokenPair
{
    /**
     * ID клиента.
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
     * Секретный ключ.
     *
     * @var string|null
     */
    public ?string $secret = null;

    /**
     * Дата истечения.
     *
     * @var Carbon|null
     */
    public ?Carbon $expires_at = null;
}
