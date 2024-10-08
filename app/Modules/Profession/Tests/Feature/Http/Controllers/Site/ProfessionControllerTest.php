<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Tests\Feature\Http\Controllers\Site;

use App\Modules\Course\Tests\Feature\Http\Controllers\Site\CourseControllerTest;
use App\Modules\Profession\Models\Profession;
use App\Models\Test\TokenTest;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class ProfessionControllerTest extends TestCase
{
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
            'api/private/site/profession/get/' . $profession->id,
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
            'api/private/site/profession/link/' . $course->professions[0]->link,
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
    private function getProfessionStructure(): array
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
