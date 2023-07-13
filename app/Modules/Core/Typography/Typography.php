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
use App\Modules\Core\Typography\Tasks\FaqTask;
use App\Modules\Core\Typography\Tasks\ProfessionTask;
use App\Modules\Core\Typography\Tasks\PublicationTask;
use App\Modules\Core\Typography\Tasks\ReviewTask;
use App\Modules\Core\Typography\Tasks\SchoolTask;
use App\Modules\Core\Typography\Tasks\SkillTask;
use App\Modules\Core\Typography\Tasks\Task;
use App\Modules\Core\Typography\Tasks\ArticleTask;
use App\Modules\Core\Typography\Tasks\CategoryTask;
use App\Modules\Core\Typography\Tasks\CourseTask;
use App\Modules\Core\Typography\Tasks\DirectionTask;
use App\Modules\Core\Typography\Tasks\EmploymentTask;
use App\Modules\Core\Typography\Tasks\TeacherTask;
use App\Modules\Core\Typography\Tasks\ToolTask;

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
        $this->addTask(new CourseTask())
            ->addTask(new ArticleTask())
            ->addTask(new CategoryTask())
            ->addTask(new DirectionTask())
            ->addTask(new EmploymentTask())
            ->addTask(new FaqTask())
            ->addTask(new ProfessionTask())
            ->addTask(new PublicationTask())
            ->addTask(new ReviewTask())
            ->addTask(new SchoolTask())
            ->addTask(new SkillTask())
            ->addTask(new TeacherTask())
            ->addTask(new ToolTask());
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
