<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Tests\Feature\Http\Controllers\Site;

use App\Modules\Course\Tests\Feature\Http\Controllers\Site\CourseControllerTest;
use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
use App\Models\Test\TokenTest;
use App\Modules\Category\Models\Category;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class CategoryControllerTest extends TestCase
{
    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $category = Category::factory()->create();
        $directions = Direction::factory()->count(3)->create();
        $professions = Profession::factory()->count(4)->create();

        $category->directions()->sync($directions);
        $category->professions()->sync($professions);

        $this->json(
            'GET',
            'api/private/site/category/get/' . $category->id,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getCategoryStructure(),
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
            'api/private/site/category/get/1000',
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testLink(): void
    {
        $course = CourseControllerTest::createCourse();

        $this->json(
            'GET',
            'api/private/site/category/link/' . $course->categories[0]->link,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getCategoryStructure(true, true),
            'success',
        ]);
    }

    /**
     * Получение записи с ошибкой при отсутствии записи.
     *
     * @return void
     */
    public function testLinkNotExist(): void
    {
        $this->json(
            'GET',
            'api/private/site/category/link/test',
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
    private function getCategoryStructure(): array
    {
        return [
            'id',
            'name',
            'header',
            'header_template',
            'link',
            'text',
            'additional',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
            'directions' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'header_template',
                    'weight',
                    'link',
                    'text',
                    'additional',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'metatag'
                ]
            ],
            'professions' => [
                '*' => [
                    'id',
                    'name',
                    'header',
                    'header_template',
                    'link',
                    'text',
                    'additional',
                    'status',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'metatag'
                ]
            ]
        ];
    }
}
