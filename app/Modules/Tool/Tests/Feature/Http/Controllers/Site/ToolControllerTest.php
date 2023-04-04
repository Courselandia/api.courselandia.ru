<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Tests\Feature\Http\Controllers\Site;

use App\Modules\Course\Tests\Feature\Http\Controllers\Site\CourseControllerTest;
use App\Modules\Tool\Models\Tool;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class ToolControllerTest extends TestCase
{
    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $tool = Tool::factory()->create();

        $this->json(
            'GET',
            'api/private/site/tool/get/' . $tool->id,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getToolStructure(true, true),
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
            'api/private/site/tool/link/' . $course->tools[0]->link,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getToolStructure(true, true),
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
            'api/private/site/tool/link/test',
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
    private function getToolStructure(): array
    {
        return [
            'id',
            'name',
            'header',
            'header_template',
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
