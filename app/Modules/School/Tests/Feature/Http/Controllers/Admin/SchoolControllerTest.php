<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Tests\Feature\Http\Controllers\Admin;

use Util;
use App\Models\Test\TokenTest;
use App\Modules\School\Models\School;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для школ.
 */
class SchoolControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $school = School::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/school/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'ASC',
                ],
                'filters' => [
                    'name' => $school->name,
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getSchoolStructure()
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
        $school = School::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/school/get/'.$school->id,
            [],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSchoolStructure(),
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
            'api/private/admin/school/get/1000',
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
     * Создание данных: упрощенный вариант.
     *
     * @return void
     */
    public function testCreateSimple(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/school/create',
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSchoolStructure(),
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
            'api/private/admin/school/create',
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'site' => $faker->url(),
                'rating' => 3.56,
                'status' => true,
                'imageLogo' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
                'imageSite' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSchoolStructure(true),
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
            'api/private/admin/school/create',
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'site' => $faker->url(),
                'rating' => 3.56,
                'status' => true,
                'imageLogo' => UploadedFile::fake()->image('school.mp4'),
                'imageSite' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
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
        $school = School::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/school/update/'.$school->id,
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'site' => $faker->url(),
                'rating' => 3.56,
                'status' => true,
                'imageLogo' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
                'imageSite' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSchoolStructure(true),
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
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/school/update/'.$school->id,
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'site' => $faker->url(),
                'rating' => 3.56,
                'status' => true,
                'imageLogo' => UploadedFile::fake()->image('school.mp4'),
                'imageSite' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
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
            'api/private/admin/school/update/1000',
            [
                'name' => $faker->text(191),
                'header_template' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'site' => $faker->url(),
                'rating' => 3.56,
                'status' => true,
                'imageLogo' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
                'imageSite' => UploadedFile::fake()->image('school.jpg', 1500, 1500),
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
        $school = School::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/school/update/status/'.$school->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSchoolStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $school = School::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/school/update/status/'.$school->id,
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
            'api/private/admin/school/update/status/1000',
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
        $school = School::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/school/destroy',
            [
                'ids' => [$school->id],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных школы.
     *
     * @param  bool  $image  Добавить структуру данных изображения.
     *
     * @return array Массив структуры данных школы.
     */
    #[Pure] private function getSchoolStructure(bool $image = false): array
    {
        $structure = [
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
            'metatag'
        ];

        if ($image) {
            $structure['image_logo_id'] = $this->getImageStructure();
            $structure['image_site_id'] = $this->getImageStructure();
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
