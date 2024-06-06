<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Widget\Models\Widget;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для виджетов.
 */
class WidgetControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $widget = Widget::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/widget/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'name' => $widget->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getWidgetStructure(),
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
        $widget = Widget::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/widget/get/' . $widget->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getWidgetStructure(),
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
            'api/private/admin/widget/get/1000',
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(404)->assertJsonStructure([
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
        $widget = Widget::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/widget/update/' . $widget->id,
            [
                'name' => $faker->text(150),
                'status' => true,
                'values' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getWidgetStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $widget = Widget::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/widget/update/' . $widget->id,
            [
                'name' => $faker->text(150),
                'status' => 'test',
                'values' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
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
            'api/private/admin/widget/update/1000',
            [
                'name' => $faker->text(150),
                'status' => true,
                'values' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
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
        $widget = Widget::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/widget/update/status/' . $widget->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getWidgetStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $widget = Widget::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/widget/update/status/' . $widget->id,
            [
                'status' => 'test',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
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
            'api/private/admin/widget/update/status/1000',
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Получить структуру данных навыка.
     *
     * @return array Массив структуры данных навыка.
     */
    private function getWidgetStructure(): array
    {
        return [
            'id',
            'name',
            'index',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
