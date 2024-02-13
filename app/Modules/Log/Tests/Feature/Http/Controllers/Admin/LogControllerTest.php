<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Tests\Feature\Http\Controllers\Admin;

use Log;
use App\Models\Test\TokenTest;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для логов.
 */
class LogControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $message = 'Test message';
        Log::info($message);

        $this->json(
            'GET',
            'api/private/admin/log/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'created_at' => 'DESC',
                ],
                'filters' => [
                    'message' => $message,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getLogStructure()
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Получение первой записи.
     *
     * @return array Массив данных первой записи.
     */
    public function getFirst(): array
    {
        Log::info('Test message');

        $content = $this->json(
            'GET',
            'api/private/admin/log/read',
            [
                'offset' => 0,
                'limit' => 1,
                'sorts' => [
                    'created_at' => 'DESC',
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->getContent();

        $content = json_decode($content, true);

        return $content['data'][0];
    }

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $log = $this->getFirst();

        $this->json(
            'GET',
            'api/private/admin/log/get/' . $log['id'],
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getLogStructure(),
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
            'api/private/admin/log/get/1000',
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Удаление данных.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $log = $this->getFirst();

        $this->json(
            'DELETE',
            'api/private/admin/log/destroy',
            [
                'ids' => [$log['id']],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных лога.
     *
     * @return array Массив структуры данных лога.
     */
    private function getLogStructure(): array
    {
        return [
            'id',
            'message',
            'channel',
            'level',
            'level_name',
            'unix_time',
            'datetime',
            'context',
            'extra',
            'created_at',
            'updated_at',
        ];
    }
}
