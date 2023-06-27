<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Entities;

use Carbon\Carbon;
use App\Models\Entities;
use App\Models\Entity;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Course\Entities\Course;
use App\Modules\Article\Entities\Article;

/**
 * Сущность хранения результатов анализа текста.
 */
class Analyzer extends Entity
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
    public array|null $params = null;

    /**
     * Количество попыток получить проверенный текст.
     *
     * @var int|null
     */
    public int|null $tries = 0;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public Status|null $status = null;

    /**
     * ID сущности.
     *
     * @var int|string|null
     */
    public int|string|null $analyzerable_id = null;

    /**
     * Название сущности для которой проверяется текст.
     *
     * @var int|string|null
     */
    public string|null $analyzerable_type = null;

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
     * Сущность.
     *
     * @var Course|null
     */
    #[Entities([
        '\App\Modules\Course\Models\Course' => Course::class,
        '\App\Modules\Article\Models\Article' => Article::class,
    ], 'analyzerable_type')]
    public Course|Article|null $analyzerable = null;
}
