<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Salary\Enums\Level;

/**
 * Задача для формирования JSON данных.
 */
abstract class JsonItemSectionJob extends JsonItemJob
{
    /**
     * ID сущности.
     *
     * @var string|int
     */
    protected string|int $id;

    /**
     * Элементы.
     *
     * @var string[]
     */
    protected array $items;

    /**
     * Уровень.
     *
     * @var ?Level
     */
    protected ?Level $level;

    /**
     * Признак бесплатности.
     *
     * @var bool
     */
    protected bool $free;

    /**
     * @param string $path Ссылка на файл для сохранения.
     * @param string|int $id ID сущности.
     * @param array $items Элементы.
     * @param Level|null $level Уровень.
     * @param bool $free Признак бесплатности.
     */
    public function __construct(
        string     $path,
        string|int $id,
        array      $items,
        ?Level     $level = null,
        bool       $free = false,
    )
    {
        $this->path = $path;
        $this->id = $id;
        $this->items = $items;
        $this->level = $level;
        $this->free = $free;
    }
}
