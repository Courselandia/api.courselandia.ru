<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Course\Models\Course;
use App\Modules\Review\Enums\Status;
use App\Models\Test\TokenTest;
use App\Modules\Review\Models\Review;
use App\Modules\School\Models\School;
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
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'title' => 'ASC',
                ],
                'filters' => [
                    'name' => $review->name,
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
        $school = School::factory()->create();
        $course = Course::factory()->create();
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/review/create',
            [
                'school_id' => $school->id,
                'course_id' => $course->id,
                'name' => $faker->text(191),
                'title' => $faker->text(191),
                'advantages' => $faker->text(5000),
                'disadvantages' => $faker->text(5000),
                'rating' => 4,
                'status' => Status::ACTIVE,
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
        $school = School::factory()->create();
        $course = Course::factory()->create();

        $this->json(
            'POST',
            'api/private/admin/review/create',
            [
                'school_id' => $school->id,
                'course_id' => $course->id,
                'name' => $faker->text(191),
                'title' => $faker->text(191),
                'advantages' => $faker->text(65000),
                'disadvantages' => $faker->text(65000),
                'rating' => 4,
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
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $review = Review::factory()->create();
        $school = School::factory()->create();
        $course = Course::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/' . $review->id,
            [
                'school_id' => $school->id,
                'course_id' => $course->id,
                'name' => $faker->text(191),
                'title' => $faker->text(191),
                'advantages' => $faker->text(5000),
                'disadvantages' => $faker->text(5000),
                'rating' => 5,
                'status' => Status::ACTIVE,
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
        $school = School::factory()->create();
        $course = Course::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/' . $review->id,
            [
                'school_id' => $school->id,
                'course_id' => $course->id,
                'name' => $faker->text(191),
                'title' => $faker->text(191),
                'advantages' => $faker->text(65000),
                'disadvantages' => $faker->text(65000),
                'rating' => 4,
                'status' => 'status',
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
        $school = School::factory()->create();
        $course = Course::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/review/update/1000',
            [
                'school_id' => $school->id,
                'course_id' => $course->id,
                'name' => $faker->text(191),
                'title' => $faker->text(191),
                'advantages' => $faker->text(5000),
                'disadvantages' => $faker->text(5000),
                'rating' => 4,
                'status' => Status::ACTIVE,
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
            'school_id',
            'course_id',
            'name',
            'title',
            'advantages',
            'disadvantages',
            'rating',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'school' => [
                'id',
                'metatag_id',
                'name',
                'header',
                'link',
                'text',
                'rating',
                'site',
                'status',
                'image_logo_id',
                'image_site_id',
                'created_at',
                'updated_at',
                'deleted_at',
            ],
            'course' => [
                'id',
                'uuid',
                'metatag_id',
                'school_id',
                'image_big_id',
                'image_middle_id',
                'image_small_id',
                'header',
                'text',
                'header_morphy',
                'text_morphy',
                'link',
                'url',
                'language',
                'rating',
                'price',
                'price_discount',
                'price_recurrent_price',
                'currency',
                'online',
                'employment',
                'duration',
                'duration_rate',
                'duration_unit',
                'lessons_amount',
                'modules_amount',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        ];
    }
}
