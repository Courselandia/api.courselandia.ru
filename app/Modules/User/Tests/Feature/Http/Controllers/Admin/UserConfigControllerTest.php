<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для конфигураций пользователей.
 */
class UserConfigControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $this->json(
            'GET',
            'api/private/admin/user/config/get',
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $data = [
            'number' => 1,
            'string' => 'string',
        ];

        $this->json(
            'PUT',
            'api/private/admin/user/config/update',
            [
                'configs' => json_encode([
                    'number' => 1,
                    'string' => 'string',
                ])
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertExactJson([
            'data' => $data,
            'success' => true,
        ]);
    }

    /**
     * Обновление данных с ошибкой при не валидации записи.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/user/config/update',
            [
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }
}
