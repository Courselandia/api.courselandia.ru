<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Tests\Feature\Http\Controllers\Admin;

use Util;
use App\Models\Test\TokenTest;
use App\Modules\Direction\Models\Direction;
use Faker\Factory as Faker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для направлений.
 */
class DirectionControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $direction = Direction::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/direction/read',
            [
                'search' => $direction->header,
                'start' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'link' => $direction->link,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getDirectionStructure()
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
        $direction = Direction::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/direction/get/' . $direction->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getDirectionStructure(),
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
            'api/private/admin/direction/get/1000',
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
            'api/private/admin/direction/create',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'weight' => $faker->numberBetween(1, 500),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getDirectionStructure(),
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
            'api/private/admin/direction/create',
            [
                'header' => $faker->text(150),
                'weight' => $faker->numberBetween(1, 500),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
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
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/direction/update/' . $direction->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'weight' => $faker->numberBetween(1, 500),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getDirectionStructure(),
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
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/direction/update/' . $direction->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->text(350),
                'weight' => $faker->numberBetween(1, 500),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
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
            'api/private/admin/direction/update/1000',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'weight' => $faker->numberBetween(1, 500),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
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
        $direction = Direction::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/direction/update/status/' . $direction->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getDirectionStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $direction = Direction::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/direction/update/status/' . $direction->id,
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
            'api/private/admin/direction/update/status/1000',
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
        $direction = Direction::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/direction/destroy',
            [
                'ids' => json_encode([$direction->id]),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных направления.
     *
     * @return array Массив структуры данных направления.
     */
    #[Pure] private function getDirectionStructure(): array
    {
        return [
            'id',
            'name',
            'header',
            'weight',
            'link',
            'text',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag'
        ];
    }
}
