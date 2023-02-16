<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Database\Factories;

use JetBrains\PhpStorm\ArrayShape;
use App\Modules\Employment\Models\Employment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели трудоустройства.
 */
class EmploymentFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Employment::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'name' => 'string',
        'text' => 'string',
        'status' => 'bool'
    ])] public function definition(): array
    {
        return [
            'name' => $this->faker->text(20),
            'text' => $this->faker->text(1000),
            'status' => true,
        ];
    }
}
