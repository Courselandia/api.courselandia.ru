<?php
/**
 * Модуль Зарплат.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Tests\Feature\Http\Controllers\Admin;

use App\Modules\Profession\Models\Profession;
use App\Modules\Salary\Enums\Level;
use App\Models\Test\TokenTest;
use App\Modules\Salary\Models\Salary;
use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для зарплат.
 */
class SalaryControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $salary = Salary::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/salary/read',
            [
                'offset' => 0,
                'limit' => 10,
                'sorts' => [
                    'level' => 'DESC',
                ],
                'filters' => [
                    'salary' => $salary->salary,
                ],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getSalaryStructure()
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
        $salary = Salary::factory()->create();

        $this->json(
            'GET',
            'api/private/admin/salary/get/' . $salary->id,
            [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getSalaryStructure(),
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
            'api/private/admin/salary/get/1000',
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
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/admin/salary/create',
            [
                'level' => Level::JUNIOR,
                'profession_id' => $profession->id,
                'salary' => $faker->numberBetween(10000, 1000000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSalaryStructure(),
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
        $profession = Profession::factory()->create();

        $this->json(
            'POST',
            'api/private/admin/salary/create',
            [
                'level' => 'TEST',
                'profession_id' => $profession->id,
                'salary' => $faker->numberBetween(10000, 1000000),
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
        $salary = Salary::factory()->create();
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/salary/update/' . $salary->id,
            [
                'level' => Level::JUNIOR,
                'profession_id' => $profession->id,
                'salary' => $faker->numberBetween(10000, 1000000),
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSalaryStructure(),
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $salary = Salary::factory()->create();
        $profession = Profession::factory()->create();
        $faker = Faker::create();

        $this->json(
            'PUT',
            'api/private/admin/salary/update/' . $salary->id,
            [
                'level' => 'TEST',
                'profession_id' => $profession->id,
                'salary' => $faker->numberBetween(10000, 1000000),
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
     * Обновление данных с ошибкой для несуществующей записи.
     *
     * @return void
     */
    public function testUpdateNotExist(): void
    {
        $faker = Faker::create();
        $profession = Profession::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/salary/update/1000',
            [
                'level' => Level::JUNIOR,
                'profession_id' => $profession->id,
                'salary' => $faker->numberBetween(10000, 1000000),
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
        $salary = Salary::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/salary/update/status/' . $salary->id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
            'data' => $this->getSalaryStructure(),
        ]);
    }

    /**
     * Обновление статуса с ошибкой.
     *
     * @return void
     */
    public function testUpdateStatusNotValid(): void
    {
        $salary = Salary::factory()->create();

        $this->json(
            'PUT',
            'api/private/admin/salary/update/status/' . $salary->id,
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
            'api/private/admin/salary/update/status/1000',
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
        $salary = Salary::factory()->create();

        $this->json(
            'DELETE',
            'api/private/admin/salary/destroy',
            [
                'ids' => [$salary->id],
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных зарплаты.
     *
     * @return array Массив структуры данных зарплаты.
     */
    private function getSalaryStructure(): array
    {
        return [
            'id',
            'profession_id',
            'level',
            'salary',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
            'profession' => [
                'id',
                'metatag_id',
                'name',
                'header',
                'link',
                'text',
                'status',
                'created_at',
                'updated_at',
                'deleted_at',
            ]
        ];
    }
}
