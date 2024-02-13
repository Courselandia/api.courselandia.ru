<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use Util;
use App\Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели категорий.
 */
class CategoryFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'metatag_id' => Metatag::factory(),
            'name' => $this->faker->text(150),
            'header' => $this->faker->text(160),
            'header_template' => $this->faker->text(160),
            'link' => Util::latin($this->faker->text(160)),
            'text' => $this->faker->text(1000),
            'status' => true,
        ];
    }
}
