<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Database\Factories;

use Util;
use App\Modules\Collection\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Metatag\Models\Metatag;
use App\Modules\Direction\Models\Direction;

/**
 * Фабрика модели коллекции.
 */
class CollectionFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Collection::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'metatag_id' => Metatag::factory(),
            'direction_id' => Direction::factory(),
            'name' => $this->faker->text(160),
            'link' => Util::latin($this->faker->text(160)),
            'text' => $this->faker->text(1000),
            'additional' => $this->faker->text(1000),
            'amount' => 4,
            'status' => true,
        ];
    }
}
