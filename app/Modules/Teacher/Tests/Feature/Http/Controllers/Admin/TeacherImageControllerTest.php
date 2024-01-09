<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для изображений учителя.
 */
class TeacherImageControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $teacher = Teacher::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/teacher/update/image/' . $teacher['id'],
            [
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
                'type' => 'logo',
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
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $teacher = Teacher::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/teacher/update/image/' . $teacher['id'],
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
            'api/private/admin/teacher/update/image/1000',
            [
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
                'type' => 'logo',
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
        $teacher = Teacher::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/teacher/destroy/image/' . $teacher->id,
            [
                'type' => 'logo',
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
            'api/private/admin/teacher/destroy/image/10000',
            [
                'type' => 'logo',
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
     * Получить структуру данных учителя.
     *
     * @param bool $image Добавить структуру данных изображения.
     *
     * @return array Массив структуры данных учителя.
     */
    private function getTeacherStructure(bool $image = false): array
    {
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
