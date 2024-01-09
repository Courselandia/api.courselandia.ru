<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Database\Factories;

use App\Modules\Course\Models\CourseLevel;
use App\Modules\Salary\Enums\Level;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели чему научится после курса.
 */
class CourseLevelFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = CourseLevel::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'level' => Level::JUNIOR->value,
        ];
    }
}
