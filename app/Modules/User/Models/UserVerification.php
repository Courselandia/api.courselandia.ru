<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Models;

use App\Models\Sortable;
use App\Modules\User\Database\Factories\UserVerificationFactory;
use App\Modules\User\Filters\UserVerificationFilter;
use Eloquent;
use App\Models\Validate;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс модель для таблицы верификации пользователей на основе Eloquent.
 *
 * @property int|string $id ID записи.
 * @property int $user_id ID пользователя.
 * @property string $code Код.
 * @property bool $status Статус.
 *
 * @property-read User $user
 */
class UserVerification extends Eloquent
{
    use HasFactory;
    use Sortable;
    use Status;
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
        'code',
        'status'
    ];

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(UserVerificationFilter::class);
    }

    /**
     * Метод, который должен вернуть все правила валидации.
     *
     * @return array Вернет массив правил.
     */
    #[ArrayShape([
        'user_id' => 'string',
        'code' => 'string',
        'status' => 'string'
    ])] protected function getRules(): array
    {
        return [
            'user_id' => 'required|integer|digits_between:1,20',
            'code' => 'required|max:5000',
            'status' => 'required|boolean'
        ];
    }

    /**
     * Метод, который должен вернуть все названия атрибутов.
     *
     * @return array Массив возможных ошибок валидации.
     */
    #[ArrayShape([
        'user_id' => 'string',
        'code' => 'string',
        'status' => 'string'
    ])] protected function getNames(): array
    {
        return [
            'user_id' => trans('user::models.userVerification.userId'),
            'code' => trans('user::models.userVerification.code'),
            'status' => trans('user::models.userVerification.status')
        ];
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return UserVerificationFactory::new();
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
