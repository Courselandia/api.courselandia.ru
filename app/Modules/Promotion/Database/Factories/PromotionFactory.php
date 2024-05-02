<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Database\Factories;

use Carbon\Carbon;
use App\Modules\Promotion\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\School\Models\School;

/**
 * Фабрика модели промоакций.
 */
class PromotionFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Promotion::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'uuid' => (string) rand(1000000, 1000000000),
            'title' => $this->faker->text(160),
            'description' => $this->faker->text(300),
            'date_start' => Carbon::now()->subMonths(2),
            'date_end' => Carbon::now()->addMonths(2),
            'url' => $this->faker->url(),
            'status' => true,
        ];
    }
}
