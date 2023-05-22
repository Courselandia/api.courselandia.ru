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
     * Название категории.
     *
     * @var string|null
     */
    public ?string $category_name = null;

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
     * Текущий текст сущности.
     *
     * @var string|null
     */
    public ?string $text_current = null;

    /**
     * Шаблон запроса к искусственному интеллекту.
     *
     * @var string|null
     */
    public ?string $request_template = null;

    /**
     * Параметры.
     *
     * @var array|null
     */
    public array|null $params = null;

    /**
     * Количество попыток получить полученный текст.
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
     * @var Course|null
     */
    #[Entities(['\App\Modules\Course\Models\Course' => Course::class], 'articleable_type')]
    public Course|null $articleable = null;
}
