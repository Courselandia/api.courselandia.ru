<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Write;

use App\Models\Error;
use App\Models\Event;
use Carbon\Carbon;
use App\Modules\Article\Write\Tasks\Task;
use App\Modules\Article\Write\Tasks\CourseTextTask;

/**
 * Написание статей для разных сущностей.
 */
class Write
{
    use Error;
    use Event;

    /**
     * Задания на написания текстов.
     *
     * @var Task[]
     */
    private array $tasks = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addTask(new CourseTextTask());
    }

    /**
     * Запуск написания текстов по всем заданиям.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->runTasks();
    }

    /**
     * Получить количество генерируемых заданий на нааисание текстов.
     *
     * @return int Общее количество генерируемых заданий.
     */
    public function getTotal(): int
    {
        $tasks = $this->getTasks();
        $total = 0;

        foreach ($tasks as $task) {
            $total += $task->count();
        }

        return $total;
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Запуск заданий.
     *
     * @return void
     */
    private function runTasks(): void
    {
        $tasks = $this->getTasks();
        $now = Carbon::now();

        foreach ($tasks as $task) {
            $task->addEvent('run', function () {
                $this->fireEvent('run');
            });
        }

        foreach ($tasks as $task) {
            $now = $now->addMinute();
            $task->run($now);
        }
    }

    /**
     * Добавление задания.
     *
     * @param Task $task Задание.
     * @return $this
     */
    public function addTask(Task $task): self
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Удаление всех заданий.
     *
     * @return $this
     */
    public function clearTasks(): self
    {
        $this->tasks = [];

        return $this;
    }

    /**
     * Получение всех заданий.
     *
     * @return Task[] Массив заданий.
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }
}
