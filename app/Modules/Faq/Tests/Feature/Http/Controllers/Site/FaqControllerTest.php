<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Tests\Feature\Http\Controllers\Site;

use App\Modules\Faq\Models\Faq;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для вопрос ответа.
 */
class FaqControllerTest extends TestCase
{
    /**
     * Чтение данных.
     *
     * @return void
     */
    public function testRead(): void
    {
        $faq = Faq::factory()->create();

        $this->json(
            'GET',
            'api/private/site/faq/read/' . $faq->school->link,
        )->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => $this->getFaqStructure()
            ],
            'success',
        ]);
    }

    /**
     * Получить структуру данных вопрос-ответа.
     *
     * @return array Массив структуры данных вопрос-ответа.
     */
    private function getFaqStructure(): array
    {
        return [
            'id',
            'school_id',
            'question',
            'answer',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }
}
