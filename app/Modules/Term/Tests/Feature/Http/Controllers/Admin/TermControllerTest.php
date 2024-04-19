<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Term\Models\Term;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для терминов.
 */
class TermControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $term = Term::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/term/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'variant' => 'DESC',
                ],
                'filters' => [
                    'term' => $term->term,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getTermStructure(),
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
        $term = Term::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/term/get/' . $term->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getTermStructure(),
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
            'api/private/admin/term/get/1000',
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
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/term/create',
            [
                'variant' => $faker->text(150),
                'term' => $faker->text(150),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getTermStructure(),
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
            'api/private/admin/term/create',
            [
                'variant' => $faker->text(150),
                'term' => $faker->text(150),
                'status' => 'TEST',
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
        $term = Term::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/term/update/' . $term->id,
            [
                'variant' => $faker->text(150),
                'term' => $faker->text(150),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getTermStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $term = Term::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/term/update/' . $term->id,
            [
                'variant' => $faker->text(150),
                'term' => $faker->text(150),
                'status' => 'Test',
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
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/term/update/1000',
            [
                'variant' => $faker->text(150),
                'term' => $faker->text(150),
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
        $term = Term::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/term/update/status/' . $term->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getTermStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $term = Term::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/term/update/status/' . $term->id,
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
            'api/private/admin/term/update/status/1000',
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
        $term = Term::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/term/destroy',
            [
                'ids' => [$term->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных термина.
     *
     * @return array Массив структуры данных термина.
     */
    private function getTermStructure(): array
    {
        return [
            'id',
            'variant',
            'term',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
