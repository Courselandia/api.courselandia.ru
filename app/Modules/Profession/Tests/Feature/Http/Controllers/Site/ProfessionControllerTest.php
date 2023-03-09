<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Tests\Feature\Http\Controllers\Site;

use App\Modules\Profession\Models\Profession;
use App\Models\Test\TokenTest;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class ProfessionControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $profession = Profession::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/profession/get/' . $profession->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getProfessionStructure(true, true),
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
            'api/private/admin/category/get/1000',
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
     * Получить структуру данных категории.
     *
     * @return array Массив структуры данных категории.
     */
    private function getProfessionStructure(): array
    {
        return [
            'id',
            'name',
            'header',
            'link',
            'text',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
        ];
    }
}
