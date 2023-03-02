<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Course\Models\Course;
use Illuminate\Http\UploadedFile;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для изображений курсов.
 */
class CourseImageControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $course = Course::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/course/update/image/' . $course['id'],
            [
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getCourseStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $course = Course::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/course/update/image/' . $course['id'],
            [
                'image' => UploadedFile::fake()->image('me.mp4'),
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
        $this->json(
            'PUT',
            'api/private/admin/course/update/image/1000',
            [
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
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
     * Удаление изображения.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $course = Course::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/course/destroy/image/' . $course->id,
            [
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Удаление изображения с ошибкой при отсутствии записи.
     *
     * @return void
     */
    public function testDestroyNotExist(): void
    {
        $this->json(
            'DELETE',
            'api/private/admin/course/destroy/image/10000',
            [
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Получить структуру данных публикации.
     *
     * @param bool $image Добавить структуру данных изображения.
     *
     * @return array Массив структуры данных публикации.
     */
    #[Pure] private function getCourseStructure(bool $image = false): array
    {
        $structure = [
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
            'price_old',
            'price_recurrent',
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
