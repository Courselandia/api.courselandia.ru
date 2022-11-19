<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Database\Factories;

use App\Modules\Profession\Models\Profession;
use App\Modules\Salary\Enums\Level;
use JetBrains\PhpStorm\ArrayShape;
use App\Modules\Salary\Models\Salary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели зарплат.
 */
class SalaryFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Salary::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    #[ArrayShape([
        'profession_id' => '\Illuminate\Database\Eloquent\Factories\Factory',
        'level' => 'string',
        'salary' => 'integer',
        'status' => 'bool'
    ])] public function definition(): array
    {
        return [
            'profession_id' => Profession::factory(),
            'level' => Level::JUNIOR->value,
            'salary' => $this->faker->numberBetween(10000, 1000000),
            'status' => true,
        ];
    }
}
