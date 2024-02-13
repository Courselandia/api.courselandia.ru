<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Database\Factories;

use App\Modules\User\Models\UserVerification;
use Carbon\Carbon;
use Crypt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели верификации пользователя.
 */
class UserVerificationFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = UserVerification::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'code' => Crypt::encrypt(intval(Carbon::now()->format('U')) + rand(1000000, 100000000)),
            'status' => true
        ];
    }
}
