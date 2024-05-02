<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Tests\Feature\Http\Controllers\Admin;

use App\Modules\School\Models\School;
use Carbon\Carbon;
use App\Models\Test\TokenTest;
use App\Modules\Promotion\Models\Promotion;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для промоакций.
 */
class PromotionControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $promotion = Promotion::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/promotion/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'title' => 'DESC',
                ],
                'filters' => [
                    'title' => $promotion->title,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getPromotionStructure(),
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
        $promotion = Promotion::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/promotion/get/' . $promotion->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getPromotionStructure(),
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
            'api/private/admin/promotion/get/1000',
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
     * Создание данных.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $school = School::factory()->create();
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/promotion/create',
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPromotionStructure(),
        ]);
    }

    /**
     * Создание данных с ошибкой в данных.
     *
     * @return void
     */
    public function testCreateNotValid(): void
    {
        $school = School::factory()->create();
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/promotion/create',
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'status' => true,
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
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $school = School::factory()->create();
        $promotion = Promotion::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/promotion/update/' . $promotion->id,
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPromotionStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $school = School::factory()->create();
        $promotion = Promotion::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/promotion/update/' . $promotion->id,
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d'),
                'status' => true,
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
        $school = School::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/promotion/update/1000',
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
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
     * Обновление статуса.
     *
     * @return void
     */
    public function testUpdateStatus(): void
    {
        $promotion = Promotion::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/promotion/update/status/' . $promotion->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPromotionStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $promotion = Promotion::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/promotion/update/status/' . $promotion->id,
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
            'api/private/admin/promotion/update/status/1000',
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
     * Удаление данных.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $promotion = Promotion::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/promotion/destroy',
            [
                'ids' => [$promotion->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных навыка.
     *
     * @return array Массив структуры данных навыка.
     */
    private function getPromotionStructure(): array
    {
        return [
            'id',
            'school_id',
            'uuid',
            'title',
            'description',
            'date_start',
            'date_end',
            'status',
            'applicable',
            'created_at',
            'updated_at',
            'deleted_at',
            'school',
        ];
    }
}
