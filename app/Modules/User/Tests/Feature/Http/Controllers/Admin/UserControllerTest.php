<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Tests\Feature\Http\Controllers\Admin;

use App\Models\Enums\DateGroup;
use App\Models\Test\TokenTest;
use App\Modules\User\Enums\Role;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserRole;
use App\Modules\User\Models\UserVerification;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для пользователей.
 */
class UserControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $user = User::factory()->create();
        UserRole::factory()->count(1)
            ->for($user)
            ->create();
        UserVerification::factory()->count(1)
            ->for($user)
            ->create();

        $this->json(
            'GET',
            'api/private/admin/user/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'first_name' => 'ASC',
                    'second_name' => 'ASC',
                    'login' => 'ASC',
                ],
                'filters' => [
                    'login' => $user->login,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getUserStructure()
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $user = User::factory()->create();
        UserRole::factory()->count(1)
            ->for($user)
            ->create();
        UserVerification::factory()->count(1)
            ->for($user)
            ->create();

        $this->json(
            'GET',
            'api/private/admin/user/get/'.$user->id,
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getUserStructure(),
            'success',
        ]);
    }

    /**
     * Получение записи с ошибкой при отсутствии записи.
     *
     * @return void
     */
    public function testGetNotExist(): void
    {
        $this->json(
            'GET',
            'api/private/admin/user/get/1000',
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Создание данных.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/user/create',
            [
                'login' => $faker->email,
                'password' => $faker->password,
                'first_name' => $faker->name,
                'second_name' => $faker->lastName,
                'status' => true,
                'verified' => true,
                'role' => Role::ADMIN->value,
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
                'invitation' => true
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure(),
        ]);
    }

    /**
     * Создание данных с ошибкой в данных.
     *
     * @return void
     */
    public function testCreateNotValid(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/user/create',
            [
                'login' => $faker->email,
                'password' => $faker->password,
                'first_name' => $faker->name,
                'second_name' => $faker->lastName,
                'status' => true,
                'verified' => true,
                'role' => Role::ADMIN->value,
                'image' => UploadedFile::fake()->image('me.mp4', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $faker = Faker::create();
        $user = User::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/user/update/'.$user['id'],
            [
                'login' => $faker->email,
                'password' => $faker->password,
                'first_name' => $faker->name,
                'second_name' => $faker->lastName,
                'status' => true,
                'verified' => true,
                'role' => Role::ADMIN->value,
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $faker = Faker::create();
        $user = User::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/user/update/'.$user['id'],
            [
                'login' => $faker->email,
                'password' => $faker->password,
                'first_name' => $faker->name,
                'second_name' => $faker->lastName,
                'status' => true,
                'verified' => true,
                'role' => Role::ADMIN->value,
                'image' => UploadedFile::fake()->image('me.mp4', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/user/update/1000',
            [
                'login' => $faker->email,
                'password' => $faker->password,
                'first_name' => $faker->name,
                'second_name' => $faker->lastName,
                'status' => true,
                'verified' => true,
                'role' => Role::ADMIN->value,
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление статуса.
     *
     * @return void
     */
    public function testUpdateStatus(): void
    {
        $user = User::factory()->create();
        UserRole::factory()->count(1)
            ->for($user)
            ->create();
        UserVerification::factory()->count(1)
            ->for($user)
            ->create();

        $this->json(
            'PUT',
            'api/private/admin/user/update/status/'.$user['id'],
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $user = User::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/user/update/status/'.$user['id'],
            [
                'status' => 'test',
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление статуса с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateStatusNotExist(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/user/update/status/1000',
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление пароля.
     *
     * @return void
     */
    public function testPassword(): void
    {
        $faker = Faker::create();
        $user = User::factory()->create();
        UserRole::factory()->count(1)
            ->for($user)
            ->create();
        UserVerification::factory()->count(1)
            ->for($user)
            ->create();

        $this->json(
            'PUT',
            'api/private/admin/user/password/'.$user['id'],
            [
                'password' => $faker->password,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure(),
        ]);
    }

    /**
     * Обновление пароля с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testPasswordNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/user/password/1000',
            [
                'password' => $faker->password,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Удаление данных.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $user = User::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/user/destroy',
            [
                'ids' => [$user['id']],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных псевдонима системы.
     *
     * @return array Массив структуры данных псевдонима.
     */
    #[Pure] private function getUserStructure(): array
    {
        return [
            'id',
            'image_small_id',
            'image_middle_id',
            'image_big_id',
            'login',
            'password',
            'remember_token',
            'first_name',
            'second_name',
            'phone',
            'two_factor',
            'status',
            'flags',
            'recovery',
            'verification' => [
                'id',
                'user_id',
                'code',
                'status',
            ],
            'role' => [
                'id',
                'user_id',
                'name',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            'created_at',
            'updated_at',
            'deleted_at'
        ];
    }

    /**
     * Получить структуру данных по аналитики новых пользователей.
     *
     * @return array Массив структуры данных аналитики новых пользователей.
     */
    #[Pure] private function getAnalyticsNewUsersStructure(): array
    {
        return [
            'date_group',
            'amount'
        ];
    }
}
