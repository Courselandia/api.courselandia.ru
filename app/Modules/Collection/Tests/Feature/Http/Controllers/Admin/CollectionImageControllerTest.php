<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Collection\Models\Collection;
use App\Modules\Collection\Models\CollectionFilter;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для изображений учителя.
 */
class CollectionImageControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'PUT',
            'api/private/admin/collection/update/image/' . $collection['id'],
            [
                'image' => UploadedFile::fake()->image('me.jpg', 1000, 1000),
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
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'PUT',
            'api/private/admin/collection/update/image/' . $collection['id'],
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
            'api/private/admin/collection/update/image/1000',
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
        $collection = Collection::factory()->create();
        CollectionFilter::factory()->count(3)->for($collection)->create();

        $this->json(
            'DELETE',
            'api/private/admin/collection/destroy/image/' . $collection->id,
            [],
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
            'api/private/admin/collection/destroy/image/10000',
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
            'sort_field',
            'sort_direction',
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
