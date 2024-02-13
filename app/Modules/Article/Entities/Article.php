<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Entities;

use Carbon\Carbon;
use App\Models\Entity;
use App\Modules\Analyzer\Entities\Analyzer;
use App\Modules\Article\Enums\Status;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

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
     * Метка сущности.
     *
     * @var string|null
     */
    public ?string $category_label = null;

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
     * Количество попыток получить написанный текст.
     *
     * @var int|null
     */
    public int|null $tries = null;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public Status|null $status = null;

    /**
     * ID сущности для которой написана статья
     *
     * @var int|string|null
     */
    public int|string|null $articleable_id = null;

    /**
     * Название сущности для которой написана статья.
     *
     * @var string|null
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
     * @var array|null
     */
    public ?array $articleable = null;

    /**
     * Анализ хранения текстов.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Analyzer::class)]
    public ?DataCollection $analyzers = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $task_id ID задачи на написания текста.
     * @param string|null $category Категория.
     * @param string|null $category_name Название категории.
     * @param string|null $category_label Метка сущности.
     * @param string|null $request Запрос на написание текста.
     * @param string|null $text Текст.
     * @param string|null $text_current Текущий текст сущности.
     * @param string|null $request_template Шаблон запроса к искусственному интеллекту.
     * @param array|null $params Параметры.
     * @param int|null $tries Количество попыток получить написанный текст.
     * @param Status|null $status Статус.
     * @param int|string|null $articleable_id ID сущности для которой написана статья.
     * @param string|null $articleable_type Название сущности для которой написана статья.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param mixed|null $articleable Сущность.
     * @param DataCollection|null $analyzers Анализ хранения текстов.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $task_id = null,
        ?string         $category = null,
        ?string         $category_name = null,
        ?string         $category_label = null,
        ?string         $request = null,
        ?string         $text = null,
        ?string         $text_current = null,
        ?string         $request_template = null,
        array|null      $params = null,
        int|null        $tries = 0,
        Status|null     $status = null,
        int|string|null $articleable_id = null,
        string|null     $articleable_type = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?array           $articleable = null,
        ?DataCollection $analyzers = null,
    )
    {
        $this->id = $id;
        $this->task_id = $task_id;
        $this->category = $category;
        $this->category_name = $category_name;
        $this->category_label = $category_label;
        $this->request = $request;
        $this->text = $text;
        $this->text_current = $text_current;
        $this->request_template = $request_template;
        $this->params = $params;
        $this->tries = $tries;
        $this->status = $status;
        $this->articleable_id = $articleable_id;
        $this->articleable_type = $articleable_type;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->articleable = $articleable;
        $this->analyzers = $analyzers;
    }
}
