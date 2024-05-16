<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\School\Models\School;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для изображений школ.
 */
class SchoolImageControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $school = School::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/school/update/image/' . $school['id'],
            [
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
                'type' => 'logo',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSchoolStructure(),
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

        $this->json(
            'PUT',
            'api/private/admin/school/update/image/' . $school['id'],
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
            'api/private/admin/school/update/image/1000',
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
        $school = School::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/school/destroy/image/' . $school->id,
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
            'api/private/admin/school/destroy/image/10000',
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
     * Получить структуру данных школы.
     *
     * @param bool $image Добавить структуру данных изображения.
     *
     * @return array Массив структуры данных школы.
     */
    private function getSchoolStructure(bool $image = false): array
    {
        $structure = [
            'id',
            'metatag_id',
            'name',
            'header',
            'header_template',
            'link',
            'text',
            'additional',
            'rating',
            'site',
            'referral',
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
