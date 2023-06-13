<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use App\Modules\Analyzer\Models\Analyzer;
use Faker\Factory as Faker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для анализа текстов.
 */
class AnalyzerControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $analyzer = Analyzer::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/analyzer/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'id' => 'DESC',
                ],
                'filters' => [
                    'category' => $analyzer->category,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getAnalyzerStructure()
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
        $analyzer = Analyzer::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/analyzer/get/' . $analyzer->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getAnalyzerStructure(),
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
            'api/private/admin/analyzer/get/1000',
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
     * Провести анализ текста.
     *
     * @return void
     */
    public function testAnalyze(): void
    {
        $analyzer = Analyzer::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/analyzer/analyze/' . $analyzer->id,
            [
                'request' => $faker->text(),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getAnalyzerStructure(),
        ]);
    }

    /**
     * Провести анализ текста с ошибкой.
     *
     * @return void
     */
    public function testAnalyzeNotValid(): void
    {
        $analyzer = Analyzer::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/analyzer/analyze/' . $analyzer->id, [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Провести анализ текста с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testAnalyzeNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/analyzer/analyze/1000',
            [
                'request' => $faker->text(),
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
     * Получить структуру данных статьи.
     *
     * @return array Массив структуры данных статьи.
     */
    #[Pure] private function getAnalyzerStructure(): array
    {
        return [
            'id',
            'task_id',
            'category',
            'category_name',
            'category_label',
            'request',
            'text',
            'text_current',
            'params',
            'tries',
            'status',
            'analyzerable_id',
            'analyzerable_type',
            'request_template',
            'created_at',
            'updated_at',
            'deleted_at',
            'analyzerable',
        ];
    }
}
