<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Entities;

use Carbon\Carbon;
use App\Models\EntityNew;
use App\Modules\Analyzer\Enums\Status;

/**
 * Сущность хранения результатов анализа текста.
 */
class Analyzer extends EntityNew
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID задачи на проверку текста.
     *
     * @var int|string|null
     */
    public int|string|null $task_id = null;

    /**
     * Категория.
     *
     * @var string|null
     */
    public ?string $category = null;

    /**
     * Название категории.
     *
     * @var string|null
     */
    public ?string $category_name = null;

    /**
     * Метка сущности.
     *
     * @var string|null
     */
    public ?string $category_label = null;

    /**
     * Уникальность текста.
     *
     * @var float|null
     */
    public ?float $unique = null;

    /**
     * Процент воды.
     *
     * @var int|null
     */
    public ?int $water = null;

    /**
     * Процент спама.
     *
     * @var int|null
     */
    public ?int $spam = null;

    /**
     * Текущий текст сущности.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Параметры.
     *
     * @var array|null
     */
    public ?array $params = null;

    /**
     * Количество попыток получить проверенный текст.
     *
     * @var int|null
     */
    public ?int $tries = null;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public ?Status $status = null;

    /**
     * ID сущности.
     *
     * @var int|string|null
     */
    public int|string|null $analyzerable_id = null;

    /**
     * Название сущности для которой проверяется текст.
     *
     * @var string|null
     */
    public ?string $analyzerable_type = null;

    /**
     * Статус сущности.
     *
     * @var int|bool|string|null
     */
    public string|bool|null $analyzerable_status = null;

    /**
     * @var mixed|null Сущность, которая была проанализирована.
     */
    public mixed $analyzerable = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $task_id ID задачи на проверку текста.
     * @param string|null $category Категория.
     * @param string|null $category_name Название категории.
     * @param string|null $category_label Метка сущности.
     * @param float|null $unique Уникальность текста.
     * @param int|null $water Процент воды.
     * @param int|null $spam Процент спама.
     * @param string|null $text Текущий текст сущности.
     * @param array|null $params Параметры.
     * @param int|null $tries Количество попыток получить проверенный текст.
     * @param Status|null $status Статус.
     * @param int|string|null $analyzerable_id ID сущности.
     * @param string|null $analyzerable_type Название сущности для которой проверяется текст.
     * @param string|bool|null $analyzerable_status Статус сущности.
     * @param mixed $analyzerable Сущность, которая была проанализирована.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null  $id = null,
        int|string|null  $task_id = null,
        ?string          $category = null,
        ?string          $category_name = null,
        ?string          $category_label = null,
        ?float           $unique = null,
        ?int             $water = null,
        ?int             $spam = null,
        ?string          $text = null,
        ?array           $params = null,
        ?int             $tries = null,
        Status|null      $status = null,
        int|string|null  $analyzerable_id = null,
        ?string          $analyzerable_type = null,
        string|bool|null $analyzerable_status = null,
        mixed            $analyzerable = null,
        ?Carbon          $created_at = null,
        ?Carbon          $updated_at = null,
        ?Carbon          $deleted_at = null,
    )
    {
        $this->id = $id;
        $this->task_id = $task_id;
        $this->category = $category;
        $this->category_name = $category_name;
        $this->category_label = $category_label;
        $this->unique = $unique;
        $this->water = $water;
        $this->spam = $spam;
        $this->text = $text;
        $this->params = $params;
        $this->tries = $tries;
        $this->status = $status;
        $this->analyzerable_id = $analyzerable_id;
        $this->analyzerable_type = $analyzerable_type;
        $this->analyzerable_status = $analyzerable_status;
        $this->analyzerable = $analyzerable;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
