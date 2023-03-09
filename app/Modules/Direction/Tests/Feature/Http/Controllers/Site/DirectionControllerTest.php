<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Tests\Feature\Http\Controllers\Site;

use App\Modules\Category\Models\Category;
use App\Modules\Direction\Models\Direction;
use App\Models\Test\TokenTest;
use App\Modules\Teacher\Models\Teacher;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class DirectionControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $category = Direction::factory()->create();
        $teachers = Teacher::factory()->count(3)->create();
        $categories = Category::factory()->count(4)->create();

        $category->teachers()->sync($teachers);
        $category->categories()->sync($categories);

        $this->json(
            'GET',
            'api/private/admin/direction/get/' . $category->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getDirectionStructure(true, true),
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
    private function getDirectionStructure(): array
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
