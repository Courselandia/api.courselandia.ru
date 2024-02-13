<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Values;

use App\Models\Value;

/**
 * Объект-значение токена.
 */
class Token extends Value
{
    /**
     * Токен доступа.
     *
     * @var string
     */
    private string $accessToken;

    /**
     * Токен обновления.
     *
     * @var string
     */
    private string $refreshToken;

    /**
     * @param string $accessToken Токен доступа.
     * @param string $refreshToken Токен обновления.
     */
    public function __construct(
        string $accessToken,
        string $refreshToken
    )
    {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
    }

    /**
     * Получение токена доступа.
     *
     * @return string Токена доступа.
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * Получение токена обновления.
     *
     * @return string Токена обновления.
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }
}
