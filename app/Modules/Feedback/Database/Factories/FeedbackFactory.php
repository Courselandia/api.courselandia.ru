<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Database\Factories;

use App\Modules\Feedback\Models\Feedback;
use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Фабрика модели обратной связи.
 */
class FeedbackFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Feedback::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'message' => 'string'
    ])] public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'phone' => '+7-909-802-3001',
            'message' => $this->faker->text(1500)
        ];
    }
}
