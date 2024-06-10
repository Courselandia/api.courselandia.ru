<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Crawl\Models\Crawl;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для индексации.
 */
class CrawlControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $crawl = Crawl::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/crawl/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'page_id' => 'ASC',
                ],
                'filters' => [
                    'page_id' => $crawl->page_id,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ],
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getCrawlStructure(),
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Планирование индексации.
     *
     * @return void
     */
    public function testPlan(): void
    {
        $this->json(
            'POST',
            'api/private/admin/crawl/plan',
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken(),
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных направления.
     *
     * @return array Массив структуры данных направления.
     */
    private function getCrawlStructure(): array
    {
        return [
            'id',
            'page_id',
            'pushed_at',
            'engine',
            'created_at',
            'updated_at',
            'deleted_at',
            'page',
        ];
    }
}
