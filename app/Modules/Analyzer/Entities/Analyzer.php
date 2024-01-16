<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Entities;

use App\Modules\Analyzer\Casts\Analyzerable;
use Attribute;
use Carbon\Carbon;
use App\Models\EntityNew;
use App\Modules\Analyzer\Enums\Status;
use App\Modules\Course\Entities\Course;
use App\Modules\Article\Entities\Article;
use App\Modules\Skill\Entities\Skill;
use App\Modules\Tool\Entities\Tool;
use App\Modules\Direction\Entities\Direction;
use App\Modules\Profession\Entities\Profession;
use App\Modules\Category\Entities\Category;
use App\Modules\School\Entities\School;
use App\Modules\Teacher\Entities\Teacher;
use Spatie\LaravelData\Attributes\WithCast;

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
     * @var int|string|null
     */
    public ?string $analyzerable_type = null;

    /**
     * Статус сущности.
     *
     * @var int|bool|string|null
     */
    public string|bool|null $analyzerable_status = null;

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
    #[WithCast(Analyzerable::class)]
    public array|null $analyzerable = null;

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
        ?Carbon          $created_at = null,
        ?Carbon          $updated_at = null,
        ?Carbon          $deleted_at = null,
        array|null       $analyzerable = null,
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
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->analyzerable = $analyzerable;
    }
}
