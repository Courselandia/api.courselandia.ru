<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use Util;
use JetBrains\PhpStorm\ArrayShape;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели школ.
 */
class SchoolFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = School::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'metatag_id' => '\Illuminate\Database\Eloquent\Factories\Factory',
        'name' => 'string',
        'header' => 'string',
        'link' => 'string',
        'text' => 'string',
        'rating' => 'float',
        'site' => 'string',
        'status' => 'bool'
    ])] public function definition(): array
    {
        return [
            'metatag_id' => Metatag::factory(),
            'name' => $this->faker->text(160),
            'header' => $this->faker->text(160),
            'link' => Util::latin($this->faker->text(160)),
            'text' => $this->faker->text(1000),
            'rating' => 4.27,
            'site' => $this->faker->url(),
            'status' => true,
        ];
    }
}
