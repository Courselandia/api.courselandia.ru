<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Database\Factories;

use Util;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Metatag\Models\Metatag;
use App\Modules\School\Models\School;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели публикаций.
 */
class CourseFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'uuid' => (string) rand(1000000, 1000000000),
            'metatag_id' => Metatag::factory(),
            'school_id' => School::factory(),
            'header' => $this->faker->text(40),
            'text' => $this->faker->text(1000),
            'link' => Util::latin($this->faker->text(100)),
            'url' => $this->faker->url(),
            'language' => Language::RU->value,
            'rating' => 3.14,
            'price' => $this->faker->numberBetween(10000, 1000000),
            'price_old' => $this->faker->numberBetween(10000, 1000000),
            'price_recurrent' => $this->faker->numberBetween(10000, 9000),
            'currency' => Currency::RUB->value,
            'online' => true,
            'employment' => true,
            'duration' => 2,
            'duration_unit' => Duration::WEEK->value,
            'lessons_amount' => 200,
            'modules_amount' => 5,
            'status' => Status::ACTIVE->value,
        ];
    }
}
