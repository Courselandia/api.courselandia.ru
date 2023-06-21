<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Tests\Feature\Http\Controllers\Admin;

use Tests\TestCase;
use App\Models\Test\TokenTest;

/**
 * Тестирование: Класс контроллер для анализа текста.
 */
class PlagiarismControllerTest extends TestCase
{
    use TokenTest;

    /**
     * Анализа текста.
     *
     * @return void
     */
    public function testRequest(): void
    {
        $this->json(
            'POST',
            'api/private/admin/plagiarism/request',
            [
                'text' => 'Test'
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
     * Получение результата анализа текста.
     *
     * @return void
     */
    public function testResult(): void
    {
        $this->json(
            'GET',
            'api/private/admin/plagiarism/result/1', [],
            [
                'Authorization' => 'Bearer ' . $this->getAdminToken()
            ]
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                'unique',
                'water',
                'spam',
            ],
            'success',
        ]);
    }
}
