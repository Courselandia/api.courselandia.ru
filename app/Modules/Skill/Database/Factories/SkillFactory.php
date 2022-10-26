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
use JetBrains\PhpStorm\ArrayShape;
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
