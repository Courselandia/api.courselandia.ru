<?php
/**
 * Модуль Профессий.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Tests\Feature\Http\Controllers\Admin;

use Util;
use App\Models\Test\TokenTest;
use App\Modules\Profession\Models\Profession;
use Faker\Factory as Faker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для профессий.
 */
class ProfessionControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $profession = Profession::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/profession/read',
            [
                'search' => $profession->header,
                'start' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'link' => $profession->link,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getProfessionStructure()
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
        $profession = Profession::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/profession/get/' . $profession->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getProfessionStructure(),
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
            'api/private/admin/profession/get/1000',
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
            'api/private/admin/profession/create',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getProfessionStructure(),
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
            'api/private/admin/profession/create',
            [
                'header' => $faker->text(150),
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
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/profession/update/' . $profession->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getProfessionStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/profession/update/' . $profession->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->realTextBetween(350, 500),
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
            'api/private/admin/profession/update/1000',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
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
        $profession = Profession::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/profession/update/status/' . $profession->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getProfessionStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $profession = Profession::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/profession/update/status/' . $profession->id,
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
            'api/private/admin/profession/update/status/1000',
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
        $profession = Profession::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/profession/destroy',
            [
                'ids' => json_encode([$profession->id]),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных профессии.
     *
     * @return array Массив структуры данных профессии.
     */
    #[Pure] private function getProfessionStructure(): array
    {
        return [
            'id',
            'name',
            'header',
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
