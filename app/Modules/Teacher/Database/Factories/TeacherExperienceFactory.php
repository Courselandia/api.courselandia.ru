<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Database\Factories;

use Carbon\Carbon;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Teacher\Models\TeacherExperience;

/**
 * Фабрика модели опыта работы учителя.
 */
class TeacherExperienceFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = TeacherExperience::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'teacher_id' => Teacher::factory(),
            'place' => $this->faker->text(160),
            'position' => $this->faker->text(160),
            'started' => Carbon::now()->subMonths(5),
            'finished' => Carbon::now()->subMonths(2),
            'weight' => $this->faker->numberBetween(1, 20),
        ];
    }
}
