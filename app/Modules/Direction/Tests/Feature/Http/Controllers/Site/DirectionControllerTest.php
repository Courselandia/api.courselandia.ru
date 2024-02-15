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
use App\Modules\Course\Tests\Feature\Http\Controllers\Site\CourseControllerTest;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class DirectionControllerTest extends TestCase
{
    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $direction = Direction::factory()->create();
        $teachers = Teacher::factory()->count(3)->create();
        $categories = Category::factory()->count(4)->create();

        $direction->teachers()->sync($teachers);
        $direction->categories()->sync($categories);

        $this->json(
            'GET',
            'api/private/site/direction/get/' . $direction->id,
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
            'api/private/site/direction/link/' . $course->directions[0]->link,
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
    public function testLinkNotExist(): void
    {
        $this->json(
            'GET',
            'api/private/site/direction/link/test',
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
            'header_template',
            'link',
            'text',
            'additional',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
        ];
    }
}
