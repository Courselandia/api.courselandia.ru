<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Rewrite;

use App\Modules\Article\Write\Write;
use App\Modules\Article\Write\Tasks\Task;
use App\Modules\Article\Rewrite\Tasks\CourseTextTask;
use App\Modules\Article\Rewrite\Tasks\SkillTextTask;
use App\Modules\Article\Rewrite\Tasks\ToolTextTask;

/**
 * Переписывание статей для разных сущностей.
 */
class Rewrite extends Write
{
    /**
     * Задания на переписание текстов.
     *
     * @var Task[]
     */
    private array $tasks = [];

    /**
     * Нижний порог уникальность, который нужно переписывать.
     *
     * @var int|null
     */
    private ?int $unique;

    /**
     * Верхний порог количество воды, который нужно переписывать.
     *
     * @var int|null
     */
    private ?int $water;

    /**
     * Верхний порог заспамленности, который нужно переписывать.
     *
     * @var int|null
     */
    private ?int $spam;

    /**
     * Показатель креативности сети.
     *
     * @var int|null
     */
    private ?int $creative;

    /**
     * Конструктор.
     *
     * @param ?int $unique Нижний порог уникальность, который нужно переписывать.
     * @param ?int $water Верхний порог количество воды, который нужно переписывать.
     * @param ?int $spam Верхний порог заспамленности, который нужно переписывать.
     * @param ?int $creative Показатель креативности сети.
     */
    public function __construct(?int $unique, ?int $water, ?int $spam, ?int $creative)
    {
        $this->unique = $unique;
        $this->water = $water;
        $this->spam = $spam;
        $this->creative = $creative;

        $this->addTask(new CourseTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new SkillTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new ToolTextTask($this->unique, $this->water, $this->spam, $this->creative));
    }
}
