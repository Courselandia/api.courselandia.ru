<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use Util;
use App\Modules\Profession\Models\Profession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели профессий.
 */
class ProfessionFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Profession::class;

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
            'link' => Util::latin($this->faker->text(160)),
            'text' => $this->faker->text(1000),
            'additional' => $this->faker->text(1000),
            'status' => true,
        ];
    }
}
