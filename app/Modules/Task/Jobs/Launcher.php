<?php
/**
 * Модуль Менеджер Заданий.
 * Этот модуль содержит все классы для работы с заданиями.
 *
 * @package App\Modules\Task
 */

namespace App\Modules\Task\Jobs;

use Cache;
use Throwable;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Task\Models\Task;
use App\Modules\Task\Entities\Task as TaskEntity;
use App\Modules\Task\Enums\Status;
use App\Models\Exceptions\ParameterInvalidException;
use Illuminate\Foundation\Bus\PendingDispatch;

/**
 * Класс для запуска заданий через менеджер.
 */
class Launcher implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * Название задания.
     *
     * @var string
     */
    private string $name;

    /**
     * Задание, которое должно быть выполнено в менеджере задач.
     *
     * @var ShouldQueue
     */
    private ShouldQueue $task;

    /**
     * ID пользователя, запускающего задание.
     *
     * @var int
     */
    private int $userId;

    /**
     * Модель задачи.
     *
     * @var Task
     */
    private Task $taskModel;

    /**
     * @param string $name Название задания.
     * @param ShouldQueue $task Задание, которое должно быть выполнено в менеджере задач.
     * @param int $userId ID пользователя, запускающего задание.
     * @param Task $taskModel Модель задачи.
     */
    public function __construct(string $name, ShouldQueue $task, int $userId, Task $taskModel)
    {
        $this->name = $name;
        $this->task = $task;
        $this->userId = $userId;
        $this->taskModel = $taskModel;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->taskModel->launched_at = Carbon::now();
        $this->taskModel->status = Status::PROCESSING->value;
        $this->taskModel->save();

        try {
            $this->task->handle();

            $this->taskModel->finished_at = Carbon::now();
            $this->taskModel->status = Status::FINISHED->value;
            $this->taskModel->save();

            Cache::tags(['task'])->flush();
        } catch (Throwable $error) {
            $this->taskModel->finished_at = Carbon::now();
            $this->taskModel->reason = $error->getMessage();
            $this->taskModel->status = Status::FAILED->value;
            $this->taskModel->save();

            Cache::tags(['task'])->flush();
        }
    }

    /**
     * Запуск задания.
     *
     * @param mixed ...$arguments
     * @return PendingDispatch
     * @throws ParameterInvalidException
     */
    public static function dispatch(...$arguments): PendingDispatch
    {
        $taskEntity = new TaskEntity();
        $taskEntity->name = $arguments[0];
        $taskEntity->user_id = $arguments[2];
        $taskEntity->status = Status::WAITING;

        $taskModel = Task::create($taskEntity->toArray());
        Cache::tags(['task'])->flush();

        return new PendingDispatch(new static($arguments[0], $arguments[1], $arguments[2], $taskModel));
    }
}
