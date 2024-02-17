<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use App\Modules\Skill\Models\Skill;
use App\Modules\Section\Models\Section;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели разделов.
 */
class SectionFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Section::class;

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
            'text' => $this->faker->text(1000),
            'additional' => $this->faker->text(1000),
            'status' => true,
        ];
    }
}
