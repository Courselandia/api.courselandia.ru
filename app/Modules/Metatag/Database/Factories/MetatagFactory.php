<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Database\Factories;

use JetBrains\PhpStorm\ArrayShape;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\Metatag\Models\Metatag;

/**
 * Фабрика модели публикаций.
 */
class MetatagFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Metatag::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'description' => 'string',
        'keywords' => 'string',
        'title' => 'string',
    ])] public function definition(): array
    {
        return [
            'description' => $this->faker->text(1000),
            'keywords' => $this->faker->text(1000),
            'title' => $this->faker->text(500),
        ];
    }
}
