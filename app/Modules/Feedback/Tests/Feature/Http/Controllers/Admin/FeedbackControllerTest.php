<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Feedback\Models\Feedback;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для обратной связи административной системы.
 */
class FeedbackControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $feedback = Feedback::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/feedback/read',
            [
                'start' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'ASC',
                    'email' => 'ASC',
                    'phone' => 'ASC',
                ],
                'filters' => [
                    'name' => $feedback->name,
                    'email' => $feedback->email,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->_getFeedbackStructure()
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
        $feedback = Feedback::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/feedback/get/'.$feedback['id'],
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->_getFeedbackStructure(),
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
            'api/private/admin/feedback/get/1000',
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
     * Удаление данных.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $feedback = Feedback::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/feedback/destroy',
            [
                'ids' => json_encode([$feedback['id']]),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных FAQ.
     *
     * @return array Массив структуры данных FAQ.
     */
    private function _getFeedbackStructure(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'message',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
    }
}
