<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Models;

use App\Models\Sortable;
use App\Modules\User\Filters\UserRecoveryFilter;
use Eloquent;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Delete;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Класс модель для таблицы восстановления пароля пользователя на основе Eloquent.
 *
 * @property int|string $id ID записи.
 * @property int $user_id ID пользователя.
 * @property string $code Код восстановления.
 *
 * @property-read User $user
 */
class UserRecovery extends Eloquent
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
        'code'
    ];

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(UserRecoveryFilter::class);
    }

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    protected function getRules(): array
    {
        return [
            'user_id' => 'required|integer|digits_between:1,20',
            'code' => 'required|max:5000'
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
            'user_id' => trans('user::models.userRecovery.userId'),
            'code' => trans('user::models.userRecovery.code')
        ];
    }

    /**
     * Получить запись пользователя.
     *
     * @return BelongsTo Модель пользователя.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
