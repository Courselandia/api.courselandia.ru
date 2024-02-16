<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Tests\Feature\Http\Controllers\Admin;

use Util;
use App\Models\Test\TokenTest;
use App\Modules\Tool\Models\Tool;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для инструментов.
 */
class ToolControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $tool = Tool::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/tool/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'link' => $tool->link,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getToolStructure()
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
        $tool = Tool::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/tool/get/' . $tool->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getToolStructure(),
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
            'api/private/admin/tool/get/1000',
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
     * Создание данных.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/tool/create',
            [
                'name' => $faker->text(150),
                'header_template' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getToolStructure(),
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
            'api/private/admin/tool/create',
            [
                'header_template' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'status' => true,
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
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $tool = Tool::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/tool/update/' . $tool->id,
            [
                'name' => $faker->text(150),
                'header_template' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getToolStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $tool = Tool::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/tool/update/' . $tool->id,
            [
                'name' => $faker->text(150),
                'header_template' => $faker->realTextBetween(350, 500),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'status' => 'test',
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
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/tool/update/1000',
            [
                'name' => $faker->text(150),
                'header_template' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
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
        $tool = Tool::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/tool/update/status/' . $tool->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getToolStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $tool = Tool::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/tool/update/status/' . $tool->id,
            [
                'status' => 'test',
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
     * Обновление статуса с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateStatusNotExist(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/tool/update/status/1000',
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
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
        $tool = Tool::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/tool/destroy',
            [
                'ids' => [$tool->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных инструмента.
     *
     * @return array Массив структуры данных инструмента.
     */
    private function getToolStructure(): array
    {
        return [
            'id',
            'name',
            'header',
            'header_template',
            'link',
            'text',
            'additional',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag'
        ];
    }
}
