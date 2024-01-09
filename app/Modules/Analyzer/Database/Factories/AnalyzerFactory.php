<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Database\Factories;

use App\Modules\Analyzer\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Analyzer\Models\Analyzer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели написанных текстов.
 */
class AnalyzerFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Analyzer::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        $course = Course::factory()->create();

        return [
            'task_id' => $this->faker->numberBetween(),
            'category' => 'course.text',
            'unique' => $this->faker->numberBetween(1, 100),
            'water' => $this->faker->numberBetween(1, 100),
            'spam' => $this->faker->numberBetween(1, 100),
            'params' => null,
            'status' => Status::READY->value,
            'analyzerable_id' => $course->id,
            'analyzerable_type' => Course::class,
        ];
    }
}
