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
     * Конструктор.
     *
     * @param ?int $unique Нижний порог уникальность, который нужно переписывать.
     * @param ?int $water Верхний порог количество воды, который нужно переписывать.
     * @param ?int $spam Верхний порог заспамленности, который нужно переписывать.
     */
    public function __construct(?int $unique, ?int $water, ?int $spam)
    {
        $this->unique = $unique;
        $this->water = $water;
        $this->spam = $spam;

        $this->addTask(new CourseTextTask($this->unique, $this->water, $this->spam));
    }
}
