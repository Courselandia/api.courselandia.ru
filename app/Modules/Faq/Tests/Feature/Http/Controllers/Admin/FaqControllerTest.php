<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Faq\Models\Faq;
use App\Modules\School\Models\School;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для FAQ.
 */
class FaqControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $faq = Faq::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/faq/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'question' => 'ASC',
                ],
                'filters' => [
                    'question' => $faq->question,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getFaqStructure()
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
        $faq = Faq::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/faq/get/' . $faq->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getFaqStructure(),
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
            'api/private/admin/faq/get/1000',
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
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/faq/create',
            [
                'school_id' => $school->id,
                'question' => $faker->text(191),
                'answer' => $faker->text(5000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getFaqStructure(),
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

        $this->json(
            'POST',
            'api/private/admin/faq/create',
            [
                'school_id' => $school->id,
                'question' => $faker->text(191),
                'answer' => $faker->text(5000),
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
        $faq = Faq::factory()->create();
        $school = School::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/faq/update/' . $faq->id,
            [
                'school_id' => $school->id,
                'question' => $faker->text(191),
                'answer' => $faker->text(5000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getFaqStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $faq = Faq::factory()->create();
        $school = School::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/faq/update/' . $faq->id,
            [
                'school_id' => $school->id,
                'question' => $faker->text(191),
                'answer' => $faker->text(5000),
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
        $school = School::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/faq/update/1000',
            [
                'school_id' => $school->id,
                'question' => $faker->text(191),
                'answer' => $faker->text(5000),
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
        $faq = Faq::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/faq/destroy',
            [
                'ids' => [$faq->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных FAQ.
     *
     * @return array Массив структуры данных FAQ.
     */
    private function getFaqStructure(): array
    {
        return [
            'id',
            'school_id',
            'question',
            'answer',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'school' => [
                'id',
                'metatag_id',
                'name',
                'header',
                'header_template',
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
            ]
        ];
    }
}
