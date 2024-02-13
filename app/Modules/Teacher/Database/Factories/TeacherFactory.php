<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use Util;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели учителя.
 */
class TeacherFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Teacher::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'metatag_id' => Metatag::factory(),
            'name' => $this->faker->text(160),
            'city' => $this->faker->text(160),
            'comment' => $this->faker->text(160),
            'copied' => true,
            'link' => Util::latin($this->faker->text(160)),
            'text' => $this->faker->text(1000),
            'rating' => 4.27,
            'status' => true,
        ];
    }
}
