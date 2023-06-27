<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Test\TokenTest;

/**
 * Тестирование: Класс контроллер для написания текстов.
 */
class WriterControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Написания текста.
     *
     * @return void
     */
    public function testRequest(): void
    {
        $this->json(
            'POST',
            'api/private/admin/writer/request',
            [
                'request' => 'Test'
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                'id',
            ],
            'success',
        ]);
    }

    /**
     * Получение результата написания текста.
     *
     * @return void
     */
    public function testResult(): void
    {
        $this->json(
            'GET',
            'api/private/admin/writer/result/1', [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                'text',
            ],
            'success',
        ]);
    }
}
