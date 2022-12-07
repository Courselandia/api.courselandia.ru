<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Direction\Models\Direction;
use App\Modules\School\Models\School;
use Util;
use App\Models\Test\TokenTest;
use App\Modules\Teacher\Models\Teacher;
use Faker\Factory as Faker;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для учителя.
 */
class TeacherControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $teacher = Teacher::factory()->create();
        $directions = Direction::factory()->count(3)->create();
        $schools = School::factory()->count(2)->create();

        $teacher->directions()->sync($directions);
        $teacher->schools()->sync($schools);

        $this->json(
            'GET',
            'api/private/admin/teacher/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'ASC',
                ],
                'filters' => [
                    'name' => $teacher->name,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getTeacherStructure()
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
        $teacher = Teacher::factory()->create();
        $directions = Direction::factory()->count(3)->create();
        $schools = School::factory()->count(2)->create();

        $teacher->directions()->sync($directions);
        $teacher->schools()->sync($schools);

        $this->json(
            'GET',
            'api/private/admin/teacher/get/' . $teacher->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getTeacherStructure(false, true, true),
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
            'api/private/admin/teacher/get/1000',
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
     * Создание данных: упрощенный вариант.
     *
     * @return void
     */
    public function testCreateSimple(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/teacher/create',
            [
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getTeacherStructure(),
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
        $schools = School::factory()->count(2)->create();

        $this->json(
            'POST',
            'api/private/admin/teacher/create',
            [
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'rating' => 3.56,
                'status' => true,
                'image' => UploadedFile::fake()->image('teacher.jpg', 1500, 1500),
                'directions' => $directions->pluck('id'),
                'schools' => $schools->pluck('id'),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getTeacherStructure(true, true, true),
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
        $schools = School::factory()->count(2)->create();

        $this->json(
            'POST',
            'api/private/admin/teacher/create',
            [
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'rating' => 3.56,
                'status' => true,
                'image' => UploadedFile::fake()->image('teacher.mp4'),
                'directions' => $directions->pluck('id'),
                'schools' => $schools->pluck('id'),
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
        $teacher = Teacher::factory()->create();
        $faker = Faker::create();
        $directions = Direction::factory()->count(3)->create();
        $schools = School::factory()->count(2)->create();

        $this->json(
            'PUT',
            'api/private/admin/teacher/update/' . $teacher->id,
            [
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'rating' => 3.56,
                'status' => true,
                'image' => UploadedFile::fake()->image('teacher.jpg', 1500, 1500),
                'directions' => $directions->pluck('id'),
                'schools' => $schools->pluck('id'),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getTeacherStructure(true, true, true),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $teacher = Teacher::factory()->create();
        $faker = Faker::create();
        $directions = Direction::factory()->count(3)->create();
        $schools = School::factory()->count(2)->create();

        $this->json(
            'PUT',
            'api/private/admin/teacher/update/' . $teacher->id,
            [
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'rating' => 3.56,
                'status' => true,
                'image' => UploadedFile::fake()->image('teacher.mp4', 1500, 1500),
                'directions' => $directions->pluck('id'),
                'schools' => $schools->pluck('id'),
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
        $directions = Direction::factory()->count(3)->create();
        $schools = School::factory()->count(2)->create();

        $this->json(
            'PUT',
            'api/private/admin/teacher/update/1000',
            [
                'name' => $faker->text(191),
                'link' => Util::latin($faker->text(191)),
                'text' => $faker->text(1500),
                'rating' => 3.56,
                'status' => true,
                'image' => UploadedFile::fake()->image('teacher.jpg', 1500, 1500),
                'directions' => $directions->pluck('id'),
                'schools' => $schools->pluck('id'),
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
        $teacher = Teacher::factory()->create();
        $directions = Direction::factory()->count(3)->create();
        $schools = School::factory()->count(2)->create();

        $teacher->directions()->sync($directions);
        $teacher->schools()->sync($schools);

        $this->json(
            'PUT',
            'api/private/admin/teacher/update/status/' . $teacher->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getTeacherStructure(false, true, true),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $teacher = Teacher::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/teacher/update/status/' . $teacher->id,
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
            'api/private/admin/teacher/update/status/1000',
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
        $teacher = Teacher::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/teacher/destroy',
            [
                'ids' => [$teacher->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных учителя.
     *
     * @param bool $image Добавить структуру данных изображения.
     * @param bool $direction Включать в структуру данные направлений.
     * @param bool $school Включать в структуру данные школ.
     *
     * @return array Массив структуры данных учителя.
     */
    #[Pure] private function getTeacherStructure(
        bool $image = false,
        bool $direction = false,
        bool $school = false
    ): array {
        $structure = [
            'id',
            'metatag_id',
            'name',
            'link',
            'text',
            'rating',
            'status',
            'image_small_id',
            'image_middle_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag'
        ];

        if ($image) {
            $structure['image_small_id'] = $this->getImageStructure();
            $structure['image_middle_id'] = $this->getImageStructure();
        }

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

        if ($school) {
            $structure['schools'] = [
                '*' => [
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
                    'metatag'
                ]
            ];
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
