<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Rewrite;

use App\Modules\Article\Write\Write;
use App\Modules\Article\Rewrite\Tasks\CourseTextTask;
use App\Modules\Article\Rewrite\Tasks\SkillTextTask;
use App\Modules\Article\Rewrite\Tasks\ToolTextTask;
use App\Modules\Article\Rewrite\Tasks\DirectionTextTask;
use App\Modules\Article\Rewrite\Tasks\ProfessionTextTask;
use App\Modules\Article\Rewrite\Tasks\CategoryTextTask;
use App\Modules\Article\Rewrite\Tasks\SchoolTextTask;
use App\Modules\Article\Rewrite\Tasks\TeacherTextTask;
use App\Modules\Article\Rewrite\Tasks\CollectionTextTask;

/**
 * Переписывание статей для разных сущностей.
 */
class Rewrite extends Write
{
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
            ->addTask(new ToolTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new DirectionTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new ProfessionTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new CategoryTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new SchoolTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new TeacherTextTask($this->unique, $this->water, $this->spam, $this->creative))
            ->addTask(new CollectionTextTask($this->unique, $this->water, $this->spam, $this->creative));
    }
}
