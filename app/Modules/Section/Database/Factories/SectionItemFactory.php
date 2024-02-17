<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Database\Factories;

use App\Modules\Skill\Models\Skill;
use App\Modules\Section\Models\SectionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели элементов раздела.
 */
class SectionItemFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = SectionItem::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        $skill = Skill::factory()->create();

        return [
            'weight' => $this->faker->numberBetween(1, 1000),
            'itemable_id' => $skill->id,
            'itemable_type' => Skill::class,
        ];
    }
}
