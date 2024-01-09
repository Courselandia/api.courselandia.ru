<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Database\Factories;

use App\Modules\Course\Models\CourseFeature;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели особенностей курса.
 */
class CourseFeatureFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = CourseFeature::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'icon' => 'brief',
            'text' => $this->faker->text(160),
        ];
    }
}
