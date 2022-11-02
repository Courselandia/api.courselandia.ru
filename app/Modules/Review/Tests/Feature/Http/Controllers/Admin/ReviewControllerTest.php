<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Profession\Models\Profession;
use App\Modules\Review\Enums\Level;
use App\Models\Test\TokenTest;
use App\Modules\Review\Models\Review;
use Faker\Factory as Faker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для отзывов.
 */
class ReviewControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $review = Review::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/review/read',
            [
                'start' => 0,
                'limit' => 10,
                'sorts' => [
                    'level' => 'DESC',
                ],
                'filters' => [
                    'review' => $review->review,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getReviewStructure()
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
        $review = Review::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/review/get/' . $review->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getReviewStructure(),
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
            'api/private/admin/review/get/1000',
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
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/review/create',
            [
                'level' => Level::JUNIOR,
                'profession_id' => $profession->id,
                'review' => $faker->numberBetween(10000, 1000000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getReviewStructure(),
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
        $profession = Profession::factory()->create();

        $this->json(
            'POST',
            'api/private/admin/review/create',
            [
                'level' => 'TEST',
                'profession_id' => $profession->id,
                'review' => $faker->numberBetween(10000, 1000000),
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
        $review = Review::factory()->create();
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/' . $review->id,
            [
                'level' => Level::JUNIOR,
                'profession_id' => $profession->id,
                'review' => $faker->numberBetween(10000, 1000000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getReviewStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $review = Review::factory()->create();
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/' . $review->id,
            [
                'level' => 'TEST',
                'profession_id' => $profession->id,
                'review' => $faker->numberBetween(10000, 1000000),
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
        $faker = Faker::create();
        $profession = Profession::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/1000',
            [
                'level' => Level::JUNIOR,
                'profession_id' => $profession->id,
                'review' => $faker->numberBetween(10000, 1000000),
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
        $review = Review::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/status/' . $review->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getReviewStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $review = Review::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/status/' . $review->id,
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
            'api/private/admin/review/update/status/1000',
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
        $review = Review::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/review/destroy',
            [
                'ids' => [$review->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных отзывов.
     *
     * @return array Массив структуры данных отзывов.
     */
    #[Pure] private function getReviewStructure(): array
    {
        return [
            'id',
            'profession_id',
            'level',
            'review',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'profession' => [
                'id',
                'metatag_id',
                'name',
                'header',
                'link',
                'text',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        ];
    }
}
