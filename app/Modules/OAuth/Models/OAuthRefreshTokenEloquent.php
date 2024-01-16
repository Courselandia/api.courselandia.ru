<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Models;

use App\Models\Sortable;
use App\Modules\OAuth\Filters\OAuthRefreshTokenEloquentFilter;
use Eloquent;
use App\Models\Validate;
use App\Models\Delete;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Класс модель для аутентификации через API для хранения токенов обновления на основе Eloquent.
 *
 * @property int|string $id ID токена на обновления.
 * @property int $oauth_token_id ID токена.
 * @property string $refresh_token Токен обновления.
 * @property string $expires_at Дата истечения.
 *
 * @property-read OAuthTokenEloquent $token
 */
class OAuthRefreshTokenEloquent extends Eloquent
{
    use Delete;
    use Sortable;
    use Validate;
    use Filterable;
    use HasTimestamps;

    /**
     * Название таблицы базы данных.
     *
     * @var string
     */
    protected $table = 'oauth_refresh_tokens';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'oauth_token_id',
        'refresh_token',
        'expires_at'
    ];

    /**
     * Типизирование атрибутов.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'oauth_token_id' => 'required|integer|digits_between:1,20',
            'refresh_token' => 'required|between:1,500',
            'expires_at' => 'required|date'
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    protected function getNames(): array
    {
        return [
            'oauth_token_id' => trans('oauth::models.oAuthRefreshToken.oauthTokenId'),
            'refresh_token' => trans('oauth::models.oAuthRefreshToken.refreshToken'),
            'expires_at' => trans('oauth::models.oAuthRefreshToken.expiresAt')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(OAuthRefreshTokenEloquentFilter::class);
    }

    /**
     * Получить токен.
     *
     * @return HasOne Модель токенов.
     */
    public function token(): HasOne
    {
        return $this->hasOne(OAuthTokenEloquent::class, 'id', 'oauth_token_id');
    }
}
