<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography;

use App\Models\Error;
use App\Models\Event;
use App\Modules\Core\Typography\Tasks\CourseTask;
use App\Modules\Core\Typography\Tasks\Task;

/**
 * Класс для типографирования всех текстов на сайте.
 */
class Typography
{
    use Event;
    use Error;

    /**
     * Задания на типографирования.
     *
     * @var array<Task>
     */
    private array $tasks = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this
            ->addTask(new CourseTask());
    }

    /**
     * Генератор файла.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->runTasks();
    }

    /**
     * Получить количество элементов, которые нужно оттипографировать.
     *
     * @return int Общее количество типографируемых сущностей.
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
     * Запуск всех заданий на типографирования.
     *
     * @return void
     */
    private function runTasks(): void
    {
        $tasks = $this->getTasks();

        foreach ($tasks as $task) {
            $task->addEvent('finished', function () {
                $this->fireEvent('finished');
            });
        }

        foreach ($tasks as $task) {
            $task->run();
        }
    }

    /**
     * Добавление задания для типографирования.
     *
     * @param Task $task Задание на типографирование.
     * @return $this
     */
    public function addTask(Task $task): self
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Удаление задания для типографирования.
     *
     * @return $this
     */
    public function clearTasks(): self
    {
        $this->tasks = [];

        return $this;
    }

    /**
     * Получение всех заданий на типографирование.
     *
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }
}
