<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Analyze;

use App\Models\Error;
use App\Models\Event;
use Carbon\Carbon;
use App\Modules\Analyzer\Analyze\Tasks\Task;
use App\Modules\Analyzer\Analyze\Tasks\CourseTextTask;

/**
 * Анализ статей для разных сущностей.
 */
class Analyze
{
    use Error;
    use Event;

    /**
     * Задания на проведение анализа.
     *
     * @var Task[]
     */
    private array $tasks = [];

    public function __construct()
    {
        $this->addTask(new CourseTextTask());
    }

    /**
     * Запуск анализа текстов по всем заданиям.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->runTasks();
    }

    /**
     * Получить количество генерируемых заданий на анализ текстов.
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
            $total = $task->count();

            for ($i = 0; $i < $total; $i++) {
                $task->addEvent('run', function () {
                    $this->fireEvent('run');
                });

                $now = $now->addMinute();

                $task->run($now);
                $task->deleteEvent('run');
            }
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
