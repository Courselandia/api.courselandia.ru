<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Database\Factories;

use App\Modules\Term\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели терминов.
 */
class TermFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Term::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'variant' => $this->faker->text(160),
            'term' => $this->faker->text(160),
            'status' => true,
        ];
    }
}
