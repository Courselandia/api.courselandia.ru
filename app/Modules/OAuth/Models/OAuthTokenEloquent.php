<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Models;

use App\Models\Sortable;
use App\Modules\OAuth\Filters\OAuthTokenEloquentFilter;
use Eloquent;
use App\Models\Validate;
use App\Models\Delete;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Класс модель для аутентификации через API для хранения токенов на основе Eloquent.
 *
 * @property int|string $id ID токена.
 * @property int $user_id ID пользователя.
 * @property string $token Токен.
 * @property string $expires_at Дата истечения.
 *
 * @property-read OAuthRefreshTokenEloquent $refreshToken
 */
class OAuthTokenEloquent extends Eloquent
{
    use Delete;
    use Sortable;
    use Validate;
    use Filterable;

    /**
     * Название таблицы базы данных.
     *
     * @var string
     */
    protected $table = 'oauth_tokens';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'token',
        'expires_at'
    ];

    /**
     * Атрибуты, которые должны быть преобразованы к дате.
     *
     * @var array
     */
    protected $dates = [
        'expires_at'
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'user_id' => 'required|integer|digits_between:1,20',
            'token' => 'required|between:1,500',
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
            'user_id' => trans('oauth::models.oAuthToken.userId'),
            'token' => trans('oauth::models.oAuthToken.token'),
            'expires_at' => trans('oauth::models.oAuthToken.expiresAt')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(OAuthTokenEloquentFilter::class);
    }

    /**
     * Получить клиента.
     *
     * @return BelongsTo Модель пользователя.
     */
    public function refreshToken(): BelongsTo
    {
        return $this->belongsTo(OAuthRefreshTokenEloquent::class);
    }
}
