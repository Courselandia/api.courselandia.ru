<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Database\Factories;

use App\Modules\School\Models\School;
use App\Modules\Faq\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели школ.
 */
class FaqFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Faq::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'question' => $this->faker->text(191),
            'answer' => $this->faker->text(5000),
            'status' => true,
        ];
    }
}
