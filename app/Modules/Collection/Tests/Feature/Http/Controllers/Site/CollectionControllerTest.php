<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Tests\Feature\Http\Controllers\Site;

use Tests\TestCase;
use App\Modules\Collection\Models\Collection;

/**
 * Тестирование: Класс контроллер для коллекций публичной части.
 */
class CollectionControllerTest extends TestCase
{
    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        Collection::factory()->create();

        $this->json(
            'GET',
            'api/private/site/collection/read',
            [
                'limit' => 10,
                'offset' => 0,
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCollectionStructure(),
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Получение данных.
     *
     * @return void
     */
    public function testLink(): void
    {
        $collection = Collection::factory()->create();

        $this->json(
            'GET',
            'api/private/site/collection/link/' . $collection->link,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getCollectionStructure(),
            'success',
        ]);
    }

    /**
     * Получение данных с ошибкой если записи нет.
     *
     * @return void
     */
    public function testLinkNotExist(): void
    {
        $this->json(
            'GET',
            'api/private/site/publication/link/test',
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Получить структуру данных коллекции.
     *
     * @return array Массив структуры данных коллекции.
     */
    private function getCollectionStructure(): array
    {
        return [
            'id',
            'metatag_id',
            'direction_id',
            'name',
            'link',
            'text',
            'additional',
            'amount',
            'sort_field',
            'sort_direction',
            'image_small_id',
            'image_middle_id',
            'image_big_id',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
            'direction',
            'courses',
        ];
    }
}
