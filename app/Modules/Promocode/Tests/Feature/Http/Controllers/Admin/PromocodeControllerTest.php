<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;
use App\Modules\School\Models\School;
use Carbon\Carbon;
use App\Models\Test\TokenTest;
use App\Modules\Promocode\Models\Promocode;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для промокодов.
 */
class PromocodeControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $promocode = Promocode::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/promocode/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'title' => 'DESC',
                ],
                'filters' => [
                    'title' => $promocode->title,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getPromocodeStructure(),
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
        $promocode = Promocode::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/promocode/get/' . $promocode->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getPromocodeStructure(),
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
            'api/private/admin/promocode/get/1000',
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
            'api/private/admin/promocode/create',
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'code' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'min_price' => $faker->randomFloat(2, 10),
                'discount' => $faker->randomFloat(2, 10),
                'discount_type' => DiscountType::PERCENT->value,
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'type' => Type::DISCOUNT,
                'url' => $faker->url(),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPromocodeStructure(),
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
            'api/private/admin/promocode/create',
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'code' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'min_price' => $faker->randomFloat(2, 10),
                'discount' => $faker->randomFloat(2, 10),
                'discount_type' => 'test',
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'type' => Type::DISCOUNT,
                'url' => $faker->url(),
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
        $promocode = Promocode::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/promocode/update/' . $promocode->id,
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'code' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'min_price' => $faker->randomFloat(2, 10),
                'discount' => $faker->randomFloat(2, 10),
                'discount_type' => DiscountType::PERCENT->value,
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'type' => Type::DISCOUNT,
                'url' => $faker->url(),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPromocodeStructure(),
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
        $promocode = Promocode::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/promocode/update/' . $promocode->id,
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'code' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'min_price' => $faker->randomFloat(2, 10),
                'discount' => $faker->randomFloat(2, 10),
                'discount_type' => 'TEST',
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'type' => Type::DISCOUNT,
                'url' => $faker->url(),
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
            'api/private/admin/promocode/update/1000',
            [
                'school_id' => $school->id,
                'uuid' => $faker->text(150),
                'code' => $faker->text(150),
                'title' => $faker->text(150),
                'description' => $faker->text(500),
                'min_price' => $faker->randomFloat(2, 10),
                'discount' => $faker->randomFloat(2, 10),
                'discount_type' => DiscountType::PERCENT->value,
                'date_start' => Carbon::now()->addMonths(-5)->format('Y-m-d O'),
                'date_end' => Carbon::now()->addMonths(2)->format('Y-m-d O'),
                'type' => Type::DISCOUNT,
                'url' => $faker->url(),
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
        $promocode = Promocode::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/promocode/update/status/' . $promocode->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPromocodeStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $promocode = Promocode::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/promocode/update/status/' . $promocode->id,
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
            'api/private/admin/promocode/update/status/1000',
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
        $promocode = Promocode::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/promocode/destroy',
            [
                'ids' => [$promocode->id],
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
    private function getPromocodeStructure(): array
    {
        return [
            'id',
            'school_id',
            'uuid',
            'code',
            'title',
            'description',
            'min_price',
            'discount',
            'discount_type',
            'date_start',
            'date_end',
            'type',
            'url',
            'status',
            'applicable',
            'created_at',
            'updated_at',
            'deleted_at',
            'school',
        ];
    }
}
