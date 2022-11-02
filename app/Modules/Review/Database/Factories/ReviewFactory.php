<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Database\Factories;

use App\Modules\Review\Enums\Status;
use App\Modules\School\Models\School;
use JetBrains\PhpStorm\ArrayShape;
use App\Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели школ.
 */
class ReviewFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Review::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'school_id' => '\Illuminate\Database\Eloquent\Factories\Factory',
        'name' => 'string',
        'title' => 'string',
        'text' => 'string',
        'rating' => 'float',
        'status' => 'bool'
    ])] public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'name' => $this->faker->text(191),
            'title' => $this->faker->text(191),
            'text' => $this->faker->text(5000),
            'rating' => 4.27,
            'status' => Status::ACTIVE->value,
        ];
    }
}
