<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Database\Factories;

use App\Modules\User\Enums\Role;
use App\Modules\User\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели ролей пользователя.
 */
class UserRoleFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = UserRole::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => Role::USER->value,
        ];
    }
}
