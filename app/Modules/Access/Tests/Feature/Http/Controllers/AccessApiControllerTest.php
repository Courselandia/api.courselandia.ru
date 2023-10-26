<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Tests\Feature\Http\Controllers;

use Tests\TestCase;
use App\Models\Test\AccessTest;

/**
 * Тестирование: Класс контроллер для генерации ключей доступа к API.
 */
class AccessApiControllerTest extends TestCase
{
    use AccessTest;

    /**
     * Генерация токена.
     *
     * @return void
     */
    public function testToken(): void
    {
        $this->json(
            'POST',
            'api/token',
            [
                'login' => $this->getAdmin('login'),
                'password' => $this->getAdmin('password'),
                'remember' => true
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                'user' => $this->getUserStructure(),
                'accessToken',
                'refreshToken'
            ]
        ]);
    }

    /**
     * Генерация токена с ошибкой.
     *
     * @return void
     */
    public function testTokenNotValid(): void
    {
        $this->json(
            'POST',
            'api/token',
            [
                'login' => '123',
                'password' => $this->getAdmin('password'),
                'remember' => true
            ]
        )->assertStatus(401)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Генерация токена обновления.
     *
     * @return void
     */
    public function testRefresh(): void
    {
        $token = $this->json(
            'POST',
            'api/token',
            [
                'login' => $this->getAdmin('login'),
                'password' => $this->getAdmin('password'),
                'remember' => true
            ]
        )->getContent();

        $token = json_decode($token, true);

        $this->json(
            'POST',
            'api/refresh',
            [
                'refreshToken' => $token['data']['refreshToken'],
                'remember' => true
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => [
                'user' => $this->getUserStructure(),
                'accessToken',
                'refreshToken'
            ]
        ]);
    }

    /**
     * Генерация токена обновления с ошибкой.
     *
     * @return void
     */
    public function testRefreshNotValid(): void
    {
        $this->json(
            'POST',
            'api/refresh',
            [
                'refreshToken' => '123',
                'remember' => true
            ]
        )->assertStatus(401)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Получить структуру данных пользователя.
     *
     * @return array Массив структуры данных пользователя
     */
    private function getUserStructure(): array
    {
        return [
            'id',
            'image_small_id',
            'image_middle_id',
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
