<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Database\Factories;

use App\Modules\Collection\Models\CollectionFilter;
use App\Modules\Collection\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели фильтров коллекции.
 */
class CollectionFilterFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = CollectionFilter::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'collection_id' => Collection::factory(),
            'name' => $this->faker->text(160),
            'value' => $this->faker->text(160),
        ];
    }
}
