<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Database\Factories;

use App\Modules\Metatag\Models\Metatag;
use Util;
use App\Modules\Skill\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели навыков.
 */
class SkillFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Skill::class;

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
