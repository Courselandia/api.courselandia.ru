<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Models;

use App\Models\Sortable;
use App\Modules\User\Filters\UserAuthFilter;
use Eloquent;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Delete;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс модель для таблицы хранения данных об аутентификации пользователей на основе Eloquent.
 *
 * @property int|string $id
 * @property int $user_id Id пользователя.
 * @property string $os Операционная система.
 * @property string $device Устройство.
 * @property string $browser Браузер.
 * @property string $agent Агент.
 * @property string $ip IP.
 * @property float $latitude Широта.
 * @property float $longitude Долгота.
 *
 * @property-read User $user
 */
class UserAuth extends Eloquent
{
    use Delete;
    use Sortable;
    use SoftDeletes;
    use Validate;
    use Filterable;

    /**
     * Атрибуты, для которых разрешено массовое назначение.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'os',
        'device',
        'browser',
        'agent',
        'ip',
        'latitude',
        'longitude',
        'country_code',
        'region_code',
        'city',
        'zip',
    ];

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'user_id' => 'string',
        'os' => 'string',
        'device' => 'string',
        'browser' => 'string',
        'agent' => 'string',
        'ip' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'country_code' => 'string',
        'region_code' => 'string',
        'city' => 'string',
        'zip' => 'string',
    ])] protected function getRules(): array
    {
        return [
            'user_id' => 'required|integer|digits_between:1,20',
            'os' => 'nullable|max:191',
            'device' => 'nullable|max:191',
            'browser' => 'nullable|max:191',
            'agent' => 'nullable|max:191',
            'ip' => 'nullable|ip',
            'latitude' => 'nullable|float',
            'longitude' => 'nullable|float',
            'country_code' => 'nullable|max:191',
            'region_code' => 'nullable|max:191',
            'city' => 'nullable|max:191',
            'zip' => 'nullable|max:191',
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    #[ArrayShape([
        'user_id' => 'string',
        'os' => 'string',
        'device' => 'string',
        'browser' => 'string',
        'agent' => 'string',
        'ip' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'country_code' => 'string',
        'region_code' => 'string',
        'city' => 'string',
        'zip' => 'string',
    ])] protected function getNames(): array
    {
        return [
            'user_id' => trans('user::models.userAuth.userId'),
            'os' => trans('user::models.userAuth.os'),
            'device' => trans('user::models.userAuth.device'),
            'browser' => trans('user::models.userAuth.browser'),
            'agent' => trans('user::models.userAuth.agent'),
            'ip' => trans('user::models.userAuth.ip'),
            'latitude' => trans('user::models.userAuth.latitude'),
            'longitude' => trans('user::models.userAuth.longitude'),
            'country_code' => trans('user::models.userAuth.countryCode'),
            'region_code' => trans('user::models.userAuth.regionCode'),
            'city' => trans('user::models.userAuth.city'),
            'zip' => trans('user::models.userAuth.zip'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(UserAuthFilter::class);
    }

    /**
     * Получить пользователя.
     *
     * @return BelongsTo Модель пользователя.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
