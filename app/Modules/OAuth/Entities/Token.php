<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Entities;

use App\Models\Entity;
/**
 * Сущность для токена.
 */
class Token extends Entity
{
    /**
     * Токен доступа.
     *
     * @var string|null
     */
    public ?string $accessToken = null;

    /**
     * Токен обновления.
     *
     * @var string|null
     */
    public ?string $refreshToken = null;
}
