<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Models;

use App\Models\Enums\EnumList;
use App\Models\Sortable;
use App\Modules\User\Database\Factories\UserRoleFactory;
use App\Modules\User\Filters\UserRoleFilter;
use Eloquent;
use App\Models\Validate;
use App\Models\Delete;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Modules\User\Enums\Role;

/**
 * Класс модель для таблицы ролей пользователей на основе Eloquent.
 *
 * @property int|string $id Id.
 * @property int $user_id Id пользователя.
 * @property Role $name Название.
 *
 * @property-read User[] $user
 */
class UserRole extends Eloquent
{
    use Delete;
    use HasFactory;
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
        'name',
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
            'name' => 'required|in:' . implode(',' , EnumList::getValues(Role::class)),
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
            'user_id' => trans('user::models.userRole.userId'),
            'name' => trans('user::models.userRole.name'),
        ];
    }

    /**
     * Класс фильтр.
     *
     * @return string Название класса фильтра.
     */
    public function modelFilter(): string
    {
        return $this->provideFilter(UserRoleFilter::class);
    }

    /**
     * Создание новой фабрики для модели.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return UserRoleFactory::new();
    }

    /**
     * Получить пользователя этой роли.
     *
     * @return BelongsTo Пользователь.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
