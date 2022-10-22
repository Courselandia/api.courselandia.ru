<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Tests\Feature\Http\Controllers\Site;

use Faker\Factory as Faker;
use Tests\TestCase;

/**
 * Тестирование: Класс контроллер для обратной связи в публичной части.
 */
class FeedbackControllerTest extends TestCase
{
    /**
     * Отправка формы обратной связи.
     *
     * @return void
     */
    public function testSend(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/site/feedback/send',
            [
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => '+7-909-802-3001',
                'message' => $faker->text(1500)
            ],
            []
        )->assertStatus(200)->assertJsonStructure([
            'data' => $this->_getFeedbackStructure(),
            'success',
        ]);
    }

    /**
     * Отправка формы обратной связи с ошибкой.
     *
     * @return void
     */
    public function testSendNotValid(): void
    {
        $faker = Faker::create();

        $this->json(
            'POST',
            'api/private/site/feedback/send',
            [
                'name' => $faker->name,
                'phone' => '+7-909-802-3001',
                'message' => $faker->text(1500)
            ],
            []
        )->assertStatus(400)->assertJsonStructure([
            'success',
            'message'
        ]);
    }

    /**
     * Получить структуру данных обратной связи.
     *
     * @return array Массив структуры данных обратной связи.
     */
    private function _getFeedbackStructure(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'message',
            'created_at',
            'updated_at',
            'deleted_at'
        ];
    }
}
