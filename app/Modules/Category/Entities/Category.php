<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Entities;

use App\Models\Entity;
use App\Modules\Analyzer\Entities\Analyzer;
use App\Modules\Direction\Entities\Direction;
use App\Modules\Profession\Entities\Profession;
use Carbon\Carbon;
use App\Modules\Metatag\Entities\Metatag;

/**
 * Сущность для категорий.
 */
class Category extends Entity
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
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

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
     * Дполнительная информация.
     *
     * @var string|null
     */
    public ?string $additional = null;

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
     * Направления.
     *
     * @var ?array<int, Direction>
     */
    public ?array $directions = null;

    /**
     * Профессии.
     *
     * @var ?array<int, Profession>
     */
    public ?array $professions = null;

    /**
     * Анализ хранения текстов.
     *
     * @var ?array<int, Analyzer>
     */
    public ?array $analyzers = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $metatag_id ID метатегов.
     * @param string|null $name Название.
     * @param string|null $header Заголовок.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $link Ссылка.
     * @param string|null $text Статья.
     * @param string|null $additional Дополнительное описание.
     * @param Metatag|null $metatag Метатеги.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param array<int, Direction>|null $directions Направления.
     * @param array<int, Profession>|null $professions Профессии.
     * @param array<int, Analyzer>|null $analyzers Анализ хранения текстов.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $metatag_id = null,
        ?string         $name = null,
        ?string         $header = null,
        ?string         $header_template = null,
        ?string         $link = null,
        ?string         $text = null,
        ?string         $additional = null,
        ?Metatag        $metatag = null,
        ?bool           $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?array          $directions = null,
        ?array          $professions = null,
        ?array          $analyzers = null
    )
    {
        $this->id = $id;
        $this->metatag_id = $metatag_id;
        $this->name = $name;
        $this->header = $header;
        $this->header_template = $header_template;
        $this->link = $link;
        $this->text = $text;
        $this->additional = $additional;
        $this->metatag = $metatag;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->directions = $directions;
        $this->professions = $professions;
        $this->analyzers = $analyzers;
    }
}
