<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserRole;
use App\Modules\User\Models\UserVerification;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для изображений пользователей.
 */
class UserImageControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
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
            'api/private/admin/user/image/update/'.$user['id'],
            [
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
        $user = User::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/user/image/update/'.$user['id'],
            [
                'image' => UploadedFile::fake()->image('me.mp4'),
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
        $this->json(
            'PUT',
            'api/private/admin/user/image/update/1000',
            [
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
     * Удаление данных.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $user = User::factory()->create();
        UserRole::factory()->count(1)
            ->for($user)
            ->create();
        UserVerification::factory()->count(1)
            ->for($user)
            ->create();

        $this->json(
            'DELETE',
            'api/private/admin/user/image/destroy/'.$user['id'],
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getUserStructure()
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
