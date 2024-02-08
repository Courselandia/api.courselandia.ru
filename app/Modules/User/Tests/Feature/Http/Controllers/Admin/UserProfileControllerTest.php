<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для работы с профилем.
 */
class UserProfileControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/user/profile/update',
            [
                'first_name' => $faker->name,
                'second_name' => $faker->lastName,
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
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

        $this->json(
            'PUT',
            'api/private/admin/user/profile/update',
            [
                'first_name' => $faker->name,
                'second_name' => $faker->lastName,
                'image' => UploadedFile::fake()->image('me.mp4', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление изображения.
     *
     * @return void
     */
    public function testImageUpdate(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/user/profile/image/update',
            [
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure(),
        ]);
    }

    /**
     * Обновление изображения с ошибкой.
     *
     * @return void
     */
    public function testImageUpdateNotValid(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/user/profile/image/update',
            [
                'image' => UploadedFile::fake()->image('me.mp4', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Удаление изображения.
     *
     * @return void
     */
    public function testDestroyImage(): void
    {
        $this->json(
            'DELETE',
            'api/private/admin/user/profile/image/destroy',
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure()
        ]);
    }

    /**
     * Обновление пароля.
     *
     * @return void
     */
    public function testPassword(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/user/profile/password',
            [
                'password' => $this->getAdmin('password'),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure(),
        ]);
    }

    /**
     * Получить структуру данных псевдонима системы.
     *
     * @return array Массив структуры данных псевдонима.
     */
    private function getUserStructure(): array
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
            'verification',
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
}
