<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Tests\Feature\Http\Controllers\Site;

use App\Modules\School\Models\School;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для школ.
 */
class SchoolControllerTest extends TestCase
{
    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        School::factory()->create();

        $this->json(
            'GET',
            'api/private/site/school/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'ASC',
                ],
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getSchoolStructure()
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
        $school = School::factory()->create();

        $this->json(
            'GET',
            'api/private/site/school/get/' . $school->id,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSchoolStructure(),
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
            'api/private/site/school/get/1000',
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
        $school = School::factory()->create();

        $this->json(
            'GET',
            'api/private/site/school/link/' . $school->link,
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSchoolStructure(),
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
            'api/private/site/school/link/test',
        )->assertStatus(404)->assertJsonStructure([
            'data',
            'success',
        ]);
    }

    /**
     * Получить структуру данных школы.
     *
     * @return array Массив структуры данных школы.
     */
    #[Pure] private function getSchoolStructure(): array
    {
        return [
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
        ];
    }
}
