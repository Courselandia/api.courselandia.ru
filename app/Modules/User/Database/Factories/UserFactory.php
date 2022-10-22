<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Database\Factories;

use App\Modules\User\Models\User;
use App\Modules\User\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Фабрика модели пользователя.
 */
class UserFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'login' => 'string',
        'password' => 'string',
        'first_name' => 'string',
        'second_name' => 'string',
        'two_factor' => 'false',
        'status' => 'bool'
    ])] public function definition(): array
    {
        return [
            'login' => $this->faker->email,
            'password' => bcrypt('password'),
            'first_name' => $this->faker->firstName,
            'second_name' => $this->faker->lastName,
            'two_factor' => false,
            'status' => true,
        ];
    }
}
