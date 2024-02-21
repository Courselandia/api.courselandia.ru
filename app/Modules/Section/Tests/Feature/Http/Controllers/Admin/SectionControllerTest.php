<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Direction\Models\Direction;
use App\Modules\Salary\Enums\Level;
use App\Models\Test\TokenTest;
use App\Modules\Section\Models\Section;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для разделов.
 */
class SectionControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $section = Section::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/section/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'name' => $section->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getSectionStructure()
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
        $section = Section::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/section/get/' . $section->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSectionStructure(),
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
            'api/private/admin/section/get/1000',
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
        $direction = Direction::factory()->create();
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/section/create',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'level' => Level::JUNIOR->value,
                'free' => true,
                'items' => [
                    [
                        'type' => 'direction',
                        'id' => $direction->id,
                    ]
                ],
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSectionStructure(),
        ]);
    }

    /**
     * Создание данных с ошибкой в данных.
     *
     * @return void
     */
    public function testCreateNotValid(): void
    {
        $direction = Direction::factory()->create();
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/section/create',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'level' => 'TEST',
                'free' => true,
                'items' => [
                    [
                        'type' => 'direction',
                        'id' => $direction->id,
                    ]
                ],
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
        $direction = Direction::factory()->create();
        $section = Section::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/section/update/' . $section->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'level' => Level::JUNIOR->value,
                'free' => true,
                'items' => [
                    [
                        'type' => 'direction',
                        'id' => $direction->id,
                    ]
                ],
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSectionStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $direction = Direction::factory()->create();
        $section = Section::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/section/update/' . $section->id,
            [
                'name' => '',
                'header' => $faker->text(150),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'level' => 'TEST',
                'free' => true,
                'items' => [
                    [
                        'type' => 'direction',
                        'id' => $direction->id,
                    ]
                ],
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
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $direction = Direction::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/section/update/1000',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'text' => $faker->text(10000),
                'additional' => $faker->text(10000),
                'level' => Level::JUNIOR->value,
                'free' => true,
                'items' => [
                    [
                        'type' => 'direction',
                        'id' => $direction->id,
                    ]
                ],
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
        $section = Section::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/section/update/status/' . $section->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSectionStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $section = Section::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/section/update/status/' . $section->id,
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
            'api/private/admin/section/update/status/1000',
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
        $section = Section::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/section/destroy',
            [
                'ids' => [$section->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных навыка.
     *
     * @return array Массив структуры данных навыка.
     */
    private function getSectionStructure(): array
    {
        return [
            'id',
            'metatag_id',
            'name',
            'header',
            'text',
            'additional',
            'level',
            'free',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
            'items' => [
                '*' => [
                    'id',
                    'section_id',
                    'weight',
                    'itemable_id',
                    'itemable_type',
                    'itemable',
                ],
            ],
        ];
    }
}
