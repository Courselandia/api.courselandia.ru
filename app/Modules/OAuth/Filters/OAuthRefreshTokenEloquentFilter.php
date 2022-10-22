<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы аутентификации через API для хранения токенов обновления.
 */
class OAuthRefreshTokenEloquentFilter extends ModelFilter
{

}
