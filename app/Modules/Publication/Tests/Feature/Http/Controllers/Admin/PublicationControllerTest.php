<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Tests\Feature\Http\Controllers\Admin;

use Util;
use Carbon\Carbon;
use App\Models\Test\TokenTest;
use App\Modules\Publication\Models\Publication;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для публикаций.
 */
class PublicationControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $publication = Publication::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/publication/read',
            [
                'search' => $publication->header,
                'start' => 0,
                'limit' => 10,
                'sorts' => [
                    'published_at' => 'DESC',
                    'header' => 'ASC',
                ],
                'filters' => [
                    'link' => $publication->link,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getPublicationStructure()
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
        $publication = Publication::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/publication/get/'.$publication->id,
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getPublicationStructure(),
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
            'api/private/admin/publication/get/1000',
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
            'api/private/admin/publication/create',
            [
                'published_at' => Carbon::now()->addMonths(-5)->format('Y-m-d H:i:s O'),
                'header' => $faker->title,
                'link' => Util::latin($faker->title),
                'anons' => $faker->text(250),
                'article' => $faker->text(1500),
                'status' => true,
                'image' => UploadedFile::fake()->image('publication.jpg', 1500, 1500),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPublicationStructure(true),
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
            'api/private/admin/publication/create',
            [
                'published_at' => Carbon::now()->addMonths(-5)->format('Y-m-d H:i:s'),
                'header' => $faker->title,
                'link' => Util::latin($faker->title),
                'anons' => $faker->text(250),
                'article' => $faker->text(1500),
                'status' => true,
                'image' => UploadedFile::fake()->image('publication.mp4'),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
        $publication = Publication::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/publication/update/'.$publication->id,
            [
                'published_at' => Carbon::now()->addMonths(-5)->format('Y-m-d H:i:s O'),
                'header' => $faker->title,
                'link' => Util::latin($faker->title),
                'anons' => $faker->text(250),
                'article' => $faker->text(1500),
                'status' => true,
                'image' => UploadedFile::fake()->image('publication.jpg', 1500, 1500),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPublicationStructure(true),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $publication = Publication::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/publication/update/'.$publication->id,
            [
                'published_date_at' => Carbon::now()->addMonths(-5)->format('d-m-Y'),
                'published_time_at' => Carbon::now()->addMonths(-5)->format('H:i'),
                'header' => $faker->title,
                'link' => Util::latin($faker->title),
                'anons' => $faker->text(250),
                'article' => $faker->text(1500),
                'status' => true,
                'image' => UploadedFile::fake()->image('publication.jpg', 1500, 1500),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
            'api/private/admin/publication/update/1000',
            [
                'published_at' => Carbon::now()->addMonths(-5)->format('Y-m-d H:i:s O'),
                'header' => $faker->title,
                'link' => Util::latin($faker->title),
                'anons' => $faker->text(250),
                'article' => $faker->text(1500),
                'status' => true,
                'image' => UploadedFile::fake()->image('publication.jpg', 1500, 1500),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
        $publication = Publication::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/publication/update/status/'.$publication->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getPublicationStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $publication = Publication::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/publication/update/status/'.$publication->id,
            [
                'status' => 'test',
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
            'api/private/admin/publication/update/status/1000',
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
        $publication = Publication::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/publication/destroy',
            [
                'ids' => json_encode([$publication->id]),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных публикации.
     *
     * @param  bool  $image  Добавить структуру данных изображения.
     *
     * @return array Массив структуры данных публикации.
     */
    #[Pure] private function getPublicationStructure(bool $image = false): array
    {
        $structure = [
            'id',
            'metatag_id',
            'published_at',
            'header',
            'link',
            'anons',
            'article',
            'image_big_id',
            'image_middle_id',
            'image_small_id',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag'
        ];

        if ($image) {
            $structure['image_big_id'] = $this->getImageStructure();
            $structure['image_middle_id'] = $this->getImageStructure();
            $structure['image_small_id'] = $this->getImageStructure();
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
