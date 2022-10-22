<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Entities;

/**
 * Сущность для токена.
 */
class Token extends TokenPair
{
    /**
     * Секретный ключ.
     *
     * @var ?string
     */
    public ?string $secret = null;
}
