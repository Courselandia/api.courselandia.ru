<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
use Util;
use App\Models\Test\TokenTest;
use App\Modules\Category\Models\Category;
use Faker\Factory as Faker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class CategoryControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $category = Category::factory()->create();
        $directions = Direction::factory()->count(3)->create();
        $professions = Profession::factory()->count(4)->create();

        $category->directions()->sync($directions);
        $category->professions()->sync($professions);

        $this->json(
            'GET',
            'api/private/admin/category/read',
            [
                'start' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'link' => $category->link,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCategoryStructure()
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
        $category = Category::factory()->create();
        $directions = Direction::factory()->count(3)->create();
        $professions = Profession::factory()->count(4)->create();

        $category->directions()->sync($directions);
        $category->professions()->sync($professions);

        $this->json(
            'GET',
            'api/private/admin/category/get/' . $category->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getCategoryStructure(true, true),
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
            'api/private/admin/category/get/1000',
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
        $directions = Direction::factory()->count(3)->create();
        $professions = Profession::factory()->count(4)->create();

        $this->json(
            'POST',
            'api/private/admin/category/create',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
                'directions' => $directions->pluck('id'),
                'professions' => $professions->pluck('id'),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCategoryStructure(true, true),
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
        $directions = Direction::factory()->count(3)->create();

        $this->json(
            'POST',
            'api/private/admin/category/create',
            [
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
                'directions' => $directions->pluck('id'),
                'professions' => 'test',
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
        $category = Category::factory()->create();
        $faker = Faker::create();
        $directions = Direction::factory()->count(3)->create();
        $professions = Profession::factory()->count(4)->create();

        $this->json(
            'PUT',
            'api/private/admin/category/update/' . $category->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
                'directions' => $directions->pluck('id'),
                'professions' => $professions->pluck('id'),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCategoryStructure(true, true),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $category = Category::factory()->create();
        $faker = Faker::create();
        $directions = Direction::factory()->count(3)->create();

        $this->json(
            'PUT',
            'api/private/admin/category/update/' . $category->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->text(350),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => 'test',
                'directions' => $directions->pluck('id'),
                'professions' => 'test',
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
            'api/private/admin/category/update/1000',
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
        $category = Category::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/category/update/status/' . $category->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCategoryStructure(true, true),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $category = Category::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/category/update/status/' . $category->id,
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
            'api/private/admin/category/update/status/1000',
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
        $category = Category::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/category/destroy',
            [
                'ids' => [$category->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных категории.
     *
     * @param bool $direction Включать в структуру данные направлений.
     * @param bool $profession Включать в структуру данные профессий.
     *
     * @return array Массив структуры данных категории.
     */
    #[Pure] private function getCategoryStructure(bool $direction = false, bool $profession = false): array
    {
        $structure = [
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

        if ($direction) {
            $structure['directions'] = [
                '*' => [
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
                ]
            ];
        }

        if ($profession) {
            $structure['professions'] = [
                '*' => [
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
                ]
            ];
        }

        return $structure;
    }
}
