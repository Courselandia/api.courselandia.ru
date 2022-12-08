<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Database\Factories;

use App\Modules\Course\Models\CourseEmployment;
use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели трудоустройства после курса.
 */
class CourseEmploymentFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = CourseEmployment::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'text' => 'string',
    ])] public function definition(): array
    {
        return [
            'text' => $this->faker->text(80),
        ];
    }
}
