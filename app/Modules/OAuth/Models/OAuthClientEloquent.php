<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Models;

use App\Models\Sortable;
use App\Modules\OAuth\Filters\OAuthClientEloquentFilter;
use Eloquent;
use App\Models\Validate;
use App\Modules\User\Models\User;
use App\Models\Delete;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс модель для аутентификации через API для хранения клиентов на основе Eloquent.
 *
 * @property int|string $id ID клиента.
 * @property int $user_id ID пользователя.
 * @property string $secret Секрет.
 * @property string $expires_at Дата истечения.
 *
 * @property-read OAuthTokenEloquent[] $tokens
 * @property-read User $user
 */
class OAuthClientEloquent extends Eloquent
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
    protected $table = 'oauth_clients';

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'secret',
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
    #[ArrayShape([
        'user_id' => 'string',
        'secret' => 'string',
        'expires_at' => 'string'
    ])] protected function getRules(): array
    {
        return [
            'user_id' => 'required|integer|digits_between:1,20',
            'secret' => 'required|between:1,500',
            'expires_at' => 'required|date'
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    #[ArrayShape([
        'user_id' => 'string',
        'secret' => 'string',
        'expires_at' => 'string'
    ])] protected function getNames(): array
    {
        return [
            'user_id' => trans('oauth::models.oAuthClient.userId'),
            'secret' => trans('oauth::models.oAuthClient.secret'),
            'expires_at' => trans('oauth::models.oAuthClient.expiresAt')
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(OAuthClientEloquentFilter::class);
    }

    /**
     * Получить пользователя.
     *
     * @return HasOne Модель пользователя.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Получить токены.
     *
     * @return HasMany Модель токена.
     */
    public function tokens(): HasMany
    {
        return $this->hasMany(OAuthTokenEloquent::class, 'id', 'oauth_client_id');
    }
}
