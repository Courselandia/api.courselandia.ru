<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Entities;

use App\Models\Entities;
use App\Models\Entity;
use App\Modules\Analyzer\Entities\Analyzer;
use Carbon\Carbon;
use App\Modules\Metatag\Entities\Metatag;
use App\Modules\Category\Entities\Category;

/**
 * Сущность для направлений.
 */
class Direction extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID метатегов.
     *
     * @var int|string|null
     */
    public int|string|null $metatag_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header_template = null;

    /**
     * Вес.
     *
     * @var int|null
     */
    public ?int $weight = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Метатеги.
     *
     * @var Metatag|null
     */
    public ?Metatag $metatag = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * Категории.
     *
     * @var Category[]
     */
    #[Entities(Category::class)]
    public ?array $categories = null;

    /**
     * Анализ хранения текстов.
     *
     * @var Analyzer[]
     */
    #[Entities(Analyzer::class)]
    public ?array $analyzers = null;
}
