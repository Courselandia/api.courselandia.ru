<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Models;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс драйвер хранения токенов.
 */
class OAuthDriverManager extends Manager
{
    /**
     * @see Manager::getDefaultDriver
     */
    public function getDefaultDriver(): string
    {
        return Config::get('oauth.storeDriver');
    }
}
