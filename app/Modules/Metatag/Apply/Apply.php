<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply;

use Cache;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Metatag\Apply\Tasks\TaskCategory;
use App\Modules\Metatag\Apply\Tasks\TaskCourse;
use App\Modules\Metatag\Apply\Tasks\TaskDirection;
use App\Modules\Metatag\Apply\Tasks\TaskProfession;
use App\Modules\Metatag\Apply\Tasks\TaskSchool;
use App\Modules\Metatag\Apply\Tasks\TaskSkill;
use App\Modules\Metatag\Apply\Tasks\TaskTeacher;
use App\Modules\Metatag\Apply\Tasks\TaskTool;

/**
 * Массовое название мэтатегов для всех сущностей
 */
class Apply
{
    use Event;
    use Error;

    /**
     * Задержка в секундах. Сделано для снижения нагрузки на Morpher,
     * который может заблокировать при частых запросах.
     *
     * @var int
     */
    public const SLEEP = 1;

    /**
     * Задания.
     *
     * @var array<Task>
     */
    private array $tasks = [];

    /**
     * Признак того, что нужно только обновить мэтатеги на основе уже введенных шаблонов.
     *
     * @var bool
     */
    public bool $update = false;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addTask(new TaskCourse())
            ->addTask(new TaskDirection())
            ->addTask(new TaskProfession())
            ->addTask(new TaskCategory())
            ->addTask(new TaskSkill())
            ->addTask(new TaskTool())
            ->addTask(new TaskSchool())
            ->addTask(new TaskTeacher());
    }

    /**
     * Запуск процесса формирования метатэгов.
     *
     * @return void
     */
    public function do(): void
    {
        $tasks = $this->getTasks();

        foreach ($tasks as $task) {
            $task->onlyUpdate($this->onlyUpdate());

            $task->apply(function () {
                $this->fireEvent('read');
            });

            if ($task->hasError()) {
                foreach ($task->getErrors() as $error) {
                    $this->addError($error);
                }
            }
        }

        Cache::tags(['catalog', 'course'])->flush();
    }

    /**
     * Получение общего количества формируемых мэтатегов.
     *
     * @return int Количество мэтатегов для генерации.
     */
    public function count(): int
    {
        $count = 0;
        $tasks = $this->getTasks();

        foreach ($tasks as $task) {
            $count += $task->count();
        }

        return $count;
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
     * Удаление задания.
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
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * Установит или получит признак того, нужно ли только обновлять мэтатэги.
     *
     * @param ?bool $status Если указать, то изменит параметр.
     *
     * @return bool Признак обновления.
     */
    public function onlyUpdate(?bool $status = null): bool
    {
        if ($status !== null) {
            $this->update = $status;
        }

        return $this->update;
    }
}
