<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Test\TokenTest;
use App\Modules\Task\Models\Task;
use JetBrains\PhpStorm\Pure;

/**
 * Тестирование: Класс контроллер для навыков.
 */
class TaskControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $task = Task::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/task/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'name' => $task->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getTaskStructure()
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Получить структуру данных задания.
     *
     * @return array Массив структуры данных задания.
     */
    #[Pure] private function getTaskStructure(): array
    {
        return [
            'id',
            'user_id',
            'name',
            'reason',
            'status',
            'launched_at',
            'finished_at',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
