<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use Util;
use App\Modules\Direction\Models\Direction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели направлений.
 */
class DirectionFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Direction::class;

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
            'header' => $this->faker->text(160),
            'header_template' => $this->faker->text(160),
            'weight' => $this->faker->numberBetween(1, 1000),
            'link' => Util::latin($this->faker->text(160)),
            'text' => $this->faker->text(1000),
            'additional' => $this->faker->text(1000),
            'status' => true,
        ];
    }
}
