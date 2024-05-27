<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Database\Factories;

use App\Modules\Promocode\Enums\DiscountType;
use Carbon\Carbon;
use App\Modules\Promocode\Models\Promocode;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Modules\School\Models\School;
use App\Modules\Promocode\Enums\Type;

/**
 * Фабрика модели промокодов.
 */
class PromocodeFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Promocode::class;

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
            'code' => $this->faker->text(160),
            'title' => $this->faker->text(160),
            'description' => $this->faker->text(300),
            'min_price' => $this->faker->randomFloat(2, 10),
            'discount' => $this->faker->randomFloat(2, 10),
            'discount_type' => DiscountType::PERCENT->value,
            'date_start' => Carbon::now()->subMonths(2),
            'date_end' => Carbon::now()->addMonths(2),
            'type' => Type::DISCOUNT->value,
            'url' => $this->faker->url(),
            'status' => true,
        ];
    }
}
