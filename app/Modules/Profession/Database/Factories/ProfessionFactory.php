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
use JetBrains\PhpStorm\ArrayShape;
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
    #[ArrayShape([
        'metatag_id' => '\Illuminate\Database\Eloquent\Factories\Factory',
        'name' => 'string',
        'header' => 'string',
        'link' => 'string',
        'text' => 'string',
        'status' => 'bool'
    ])] public function definition(): array
    {
        return [
            'metatag_id' => Metatag::factory(),
            'name' => $this->faker->text(191),
            'header' => $this->faker->text(191),
            'link' => Util::latin($this->faker->text(191)),
            'text' => $this->faker->text(5000),
            'status' => true,
        ];
    }
}
