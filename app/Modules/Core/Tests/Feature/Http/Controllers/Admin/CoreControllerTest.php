<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Tests\Feature\Http\Controllers\Admin;

use App\Models\Test\TokenTest;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер ядра системы.
 */
class CoreControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testClean(): void
    {
        $this->json(
            'POST',
            'api/private/admin/core/clean',
            [
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJson([
            'success' => true,
        ]);
    }

    /**
     * Типографика.
     *
     * @return void
     */
    public function testTypography(): void
    {
        $this->json(
            'POST',
            'api/private/admin/core/typography/',
            [
                'text' => 'Проверка "текста" на , наличие - типографики.',
            ],
            [
                'Authorization' => 'Bearer '.$this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'text',
            'success',
        ]);
    }
}
