<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Tests\Feature\Http\Controllers\Site;

use App\Modules\Direction\Models\Direction;
use App\Modules\School\Models\School;
use App\Modules\Teacher\Models\Teacher;
use App\Models\Test\TokenTest;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для категорий.
 */
class TeacherControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Получение записи.
     *
     * @return void
     */
    public function testGet(): void
    {
        $teacher = Teacher::factory()->create();
        $directions = Direction::factory()->count(4)->create();
        $schools = School::factory()->count(1)->create();
        $teacher->directions()->sync($directions);
        $teacher->schools()->sync($schools);

        $this->json(
            'GET',
            'api/private/admin/teacher/get/' . $teacher->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getTeacherStructure(),
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
    private function getTeacherStructure(): array
    {
        return [
            'id',
            'metatag_id',
            'name',
            'link',
            'text',
            'rating',
            'status',
            'image_small_id',
            'image_middle_id',
            'created_at',
            'updated_at',
            'deleted_at',
            'metatag',
            'directions' => [
                '*' => [
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
                ],
            ],
            'schools' => [
                '*' => [
                    'id',
                    'metatag_id',
                    'name',
                    'header',
                    'link',
                    'text',
                    'rating',
                    'site',
                    'status',
                    'image_logo_id',
                    'image_site_id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                    'metatag'
                ],
            ],
        ];
    }
}
