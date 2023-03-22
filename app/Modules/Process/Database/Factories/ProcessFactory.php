<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Database\Factories;

use JetBrains\PhpStorm\ArrayShape;
use App\Modules\Process\Models\Process;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели объяснения как проходит обучение.
 */
class ProcessFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Process::class;

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
            'name' => $this->faker->text(160),
            'text' => $this->faker->text(1000),
            'status' => true,
        ];
    }
}
