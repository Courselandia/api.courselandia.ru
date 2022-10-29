<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Tests\Feature\Http\Controllers\Admin;

use Util;
use App\Models\Test\TokenTest;
use App\Modules\Skill\Models\Skill;
use Faker\Factory as Faker;
use JetBrains\PhpStorm\Pure;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для навыков.
 */
class SkillControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $skill = Skill::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/skill/read',
            [
                'start' => 0,
                'limit' => 10,
                'sorts' => [
                    'name' => 'DESC',
                ],
                'filters' => [
                    'link' => $skill->link,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getSkillStructure()
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
        $skill = Skill::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/skill/get/' . $skill->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSkillStructure(),
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
            'api/private/admin/skill/get/1000',
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
     * Создание данных.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/skill/create',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSkillStructure(),
        ]);
    }

    /**
     * Создание данных с ошибкой в данных.
     *
     * @return void
     */
    public function testCreateNotValid(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/skill/create',
            [
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $skill = Skill::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/skill/update/' . $skill->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSkillStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $skill = Skill::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/skill/update/' . $skill->id,
            [
                'name' => $faker->text(150),
                'header' => $faker->realTextBetween(350, 500),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => 'test',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/skill/update/1000',
            [
                'name' => $faker->text(150),
                'header' => $faker->text(150),
                'link' => Util::latin($faker->text(150)),
                'text' => $faker->text(10000),
                'status' => true,
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
     * Обновление статуса.
     *
     * @return void
     */
    public function testUpdateStatus(): void
    {
        $skill = Skill::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/skill/update/status/' . $skill->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSkillStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $skill = Skill::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/skill/update/status/' . $skill->id,
            [
                'status' => 'test',
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message',
        ]);
    }

    /**
     * Обновление статуса с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateStatusNotExist(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/skill/update/status/1000',
            [
                'status' => true,
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
     * Удаление данных.
     *
     * @return void
     */
    public function testDestroy(): void
    {
        $skill = Skill::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/skill/destroy',
            [
                'ids' => json_encode([$skill->id]),
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных навыка.
     *
     * @return array Массив структуры данных навыка.
     */
    #[Pure] private function getSkillStructure(): array
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
            'metatag'
        ];
    }
}
