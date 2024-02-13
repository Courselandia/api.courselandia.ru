<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Database\Factories;

use Carbon\Carbon;
use App\Modules\Task\Enums\Status;
use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели заданий.
 */
class TaskFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->text(160),
            'reason' => $this->faker->text(160),
            'status' => Status::FINISHED->value,
            'launched_at' => Carbon::now(),
            'finished_at' => Carbon::now(),
        ];
    }
}
