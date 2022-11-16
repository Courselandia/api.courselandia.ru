<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Tests\Feature\Http\Controllers\Admin;

use Alert;
use App\Models\Test\TokenTest;
use Tests\TestCase;
use Faker\Factory as Faker;

/**
 * Тестирование: Класс контроллер для сообщений.
 */
class AlertControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $faker = Faker::create();
        $name = $faker->name;
        Alert::add($name);

        $this->json(
            'GET',
            'api/private/admin/alert/read',
            [
                'offset' => 0,
                'limit' => 10,
                'unread' => true,
                'sorts' => [
                    'title' => 'ASC',
                    'description' => 'ASC',
                    'url' => 'ASC',
                ],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getAlertStructure()
            ],
            'total',
            'success',
        ]);
    }

    /**
     * Обновление данных.
     *
     * @return void
     */
    public function testStatus(): void
    {
        $faker = Faker::create();
        $name = $faker->name;
        $id = Alert::add($name);

        $this->json(
            'PUT',
            'api/private/admin/alert/status/'.$id,
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->getAlertStructure(),
            'success',
        ]);
    }

    /**
     * Обновление данных с ошибкой при несуществующей записи.
     *
     * @return void
     */
    public function testToReadNotExist(): void
    {
        $this->json(
            'PUT',
            'api/private/admin/alert/status/1000',
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(404)->assertJsonStructure([
            'message',
            'success',
        ]);
    }

    /**
     * Обновление данных с ошибкой.
     *
     * @return void
     */
    public function testUpdateNotValid(): void
    {
        $faker = Faker::create();
        $name = $faker->name;
        $id = Alert::add($name);

        $this->json(
            'PUT',
            'api/private/admin/alert/status/'.$id,
            [
                'status' => 10,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
        $this->json(
            'PUT',
            'api/private/admin/alert/update/10000',
            [
                'status' => true,
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
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
        $faker = Faker::create();
        $name = $faker->name;
        $id = Alert::add($name);

        $this->json('DELETE',
            'api/private/admin/alert/destroy',
            [
                'ids' => [$id],
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'success',
        ]);
    }

    /**
     * Получить структуру данных раздела административной системы.
     *
     * @return array Массив структуры данных раздела административной системы.
     */
    private function getAlertStructure(): array
    {
        return [
            'id',
            'title',
            'description',
            'url',
            'tag',
            'color',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
    }
}
