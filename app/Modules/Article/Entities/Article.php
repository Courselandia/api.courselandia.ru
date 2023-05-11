<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Entities;

use Carbon\Carbon;
use App\Models\Entities;
use App\Models\Entity;
use App\Modules\Article\Enums\Status;
use App\Modules\Course\Entities\Course;

/**
 * Сущность для статьи.
 */
class Article extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID задачи на написания текста.
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
     * Запрос на написание текста.
     *
     * @var string|null
     */
    public ?string $request = null;

    /**
     * Текст.
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
     * Статус.
     *
     * @var Status|null
     */
    public Status|null $status = null;

    /**
     * ID задачи на написания текста.
     *
     * @var int|string|null
     */
    public int|string|null $articleable_id = null;

    /**
     * Название сущности для которой написана статья.
     *
     * @var int|string|null
     */
    public string|null $articleable_type = null;

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
     * @var Course[]
     */
    #[Entities([Course::class])]
    public ?array $articleable = null;
}
