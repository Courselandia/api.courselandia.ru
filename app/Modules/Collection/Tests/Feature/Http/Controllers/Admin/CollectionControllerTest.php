<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Tests\Feature\Http\Controllers\Admin;

use Util;
use Tests\TestCase;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use App\Models\Test\TokenTest;
use App\Modules\Collection\Models\Collection;
use App\Modules\Collection\Models\CollectionFilter;
use App\Modules\Direction\Models\Direction;

/**
 * Тестирование: Класс контроллер для коллекций.
 */
class CollectionControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'GET',
            'api/private/admin/collection/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'ASC',
                ],
                'filters' => [
                    'name' => $collection->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCollectionStructure()
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
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'GET',
            'api/private/admin/collection/get/' . $collection->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getCollectionStructure(),
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
            'api/private/admin/collection/get/1000',
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
        $direction = Direction::factory()->create();

        $this->json(
            'POST',
            'api/private/admin/collection/create',
            [
                'direction_id' => $direction->id,
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'additional' => $faker->text(1500),
                'amount' => 10,
                'status' => true,
                'image' => UploadedFile::fake()->image('collection.jpg', 1500, 1500),
                'filters' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCollectionStructure(true),
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
        $direction = Direction::factory()->create();

        $this->json(
            'POST',
            'api/private/admin/collection/create',
            [
                'direction_id' => $direction->id,
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'additional' => $faker->text(1500),
                'amount' => 10,
                'status' => true,
                'image' => UploadedFile::fake()->image('collection.jpg', 1500, 1500),
                'filters' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
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
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/collection/update/' . $collection->id,
            [
                'direction_id' => $collection->direction->id,
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'additional' => $faker->text(1500),
                'amount' => 10,
                'status' => true,
                'image' => UploadedFile::fake()->image('collection.jpg', 1500, 1500),
                'filters' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCollectionStructure(true),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/collection/update/' . $collection->id,
            [
                'direction_id' => $collection->direction->id,
                'link' => '',
                'text' => $faker->text(1500),
                'additional' => $faker->text(1500),
                'amount' => 10,
                'status' => true,
                'image' => UploadedFile::fake()->image('collection.jpg', 1500, 1500),
                'filters' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
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
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/collection/update/1000',
            [
                'direction_id' => $collection->direction->id,
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'additional' => $faker->text(1500),
                'amount' => 10,
                'status' => true,
                'image' => UploadedFile::fake()->image('collection.jpg', 1500, 1500),
                'filters' => [
                    [
                        'name' => $faker->text(191),
                        'value' => json_encode([1, 2]),
                    ],
                ],
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
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'PUT',
            'api/private/admin/collection/update/status/' . $collection->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCollectionStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'PUT',
            'api/private/admin/collection/update/status/' . $collection->id,
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
            'api/private/admin/collection/update/status/1000',
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
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'DELETE',
            'api/private/admin/collection/destroy',
            [
                'ids' => [$collection->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных коллекции.
     *
     * @param bool $image Добавить структуру данных изображения.
     *
     * @return array Массив структуры данных учителя.
     */
    private function getCollectionStructure(bool $image = false): array
    {
        $structure = [
            'id',
            'direction_id',
            'metatag_id',
            'name',
            'link',
            'text',
            'additional',
            'amount',
            'status',
            'image_small_id',
            'image_middle_id',
            'image_big_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
            'direction',
        ];

        if ($image) {
            $structure['image_small_id'] = $this->getImageStructure();
            $structure['image_middle_id'] = $this->getImageStructure();
            $structure['image_big_id'] = $this->getImageStructure();
        }

        return $structure;
    }

    /**
     * Получить структуру данных изображения.
     *
     * @return array Массив структуры данных изображения.
     */
    private function getImageStructure(): array
    {
        return [
            'id',
            'byte',
            'folder',
            'format',
            'cache',
            'width',
            'height',
            'path',
            'pathCache',
            'pathSource'
        ];
    }
}
