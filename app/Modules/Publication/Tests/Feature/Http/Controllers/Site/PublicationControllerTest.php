<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Tests\Feature\Http\Controllers\Site;

use Carbon\Carbon;
use Tests\TestCase;
use App\Modules\Publication\Models\Publication;

/**
 * Тестирование: Класс контроллер для публикаций публичной части.
 */
class PublicationControllerTest extends TestCase
{
    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $publication = Publication::factory()->create();

        /**
         * @var Carbon $date
         */
        $date = $publication->published_at;

        $this->json(
            'GET',
            'api/private/site/publication/read',
            [
                'year' => $date->year,
                'limit' => 10,
                'offset' => 0,
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                'publications' => [
                    '*' => $this->getPublicationStructure()
                ],
                'year',
                'years' => [
                    '*' => [
                        'year',
                        'current'
                    ]
                ],
                'total'
            ],
            'success',
        ]);
    }

    /**
     * Получение данных.
     *
     * @return void
     */
    public function testGet(): void
    {
        $publication = Publication::factory()->create();

        $this->json(
            'GET',
            'api/private/site/publication/link/' . $publication->link,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getPublicationStructure(),
            'success',
        ]);
    }

    /**
     * Получение данных с ошибкой если записи нет.
     *
     * @return void
     */
    public function testGetNotExist(): void
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
     * Получить структуру данных публикации.
     *
     * @return array Массив структуры данных публикации.
     */
    private function getPublicationStructure(): array
    {
        return [
            'id',
            'metatag_id',
            'published_at',
            'header',
            'link',
            'anons',
            'article',
            'image_big',
            'image_middle',
            'image_small',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
        ];
    }
}
