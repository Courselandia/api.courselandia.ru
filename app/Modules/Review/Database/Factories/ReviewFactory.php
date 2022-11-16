<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Database\Factories;

use App\Modules\Course\Models\Course;
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
        'course_id' => '\Illuminate\Database\Eloquent\Factories\Factory',
        'name' => 'string',
        'title' => 'string',
        'text' => 'string',
        'rating' => 'string',
        'status' => 'string'
    ])] public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'course_id' => Course::factory(),
            'name' => $this->faker->text(191),
            'title' => $this->faker->text(191),
            'text' => $this->faker->text(5000),
            'rating' => rand(1, 5),
            'status' => Status::ACTIVE->value,
        ];
    }
}
