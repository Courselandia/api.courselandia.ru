<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Rewrite\Tasks;

use App\Models\Error;
use App\Models\Event;
use Carbon\Carbon;
use App\Modules\Article\Write\Tasks\Task as TaskWrite;

/**
 * Абстрактный класс для написания собственных заданий на переписание текста.
 */
abstract class Task extends TaskWrite
{
    /**
     * Нижний порог уникальность, который нужно переписывать.
     *
     * @var int|null
     */
    protected ?int $unique;

    /**
     * Верхний порог количество воды, который нужно переписывать.
     *
     * @var int|null
     */
    protected ?int $water;

    /**
     * Верхний порог заспамленности, который нужно переписывать.
     *
     * @var int|null
     */
    protected ?int $spam;

    /**
     * Показатель креативности сети.
     *
     * @var int|null
     */
    protected ?int $creative;

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
    }
}
