<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Entities;

use App\Models\Entity;
use App\Modules\Metatag\Entities\Metatag;
use App\Modules\Salary\Enums\Level;
use Carbon\Carbon;

/**
 * Модель раздела.
 */
class Section extends Entity
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
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Дополнительное описание.
     *
     * @var string|null
     */
    public ?string $additional = null;

    /**
     * Уровень.
     *
     * @var Level|null
     */
    public ?Level $level = null;

    /**
     * Признак бесплатности.
     *
     * @var bool|null
     */
    public ?bool $free = null;

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
     * URL на раздел.
     *
     * @var string|null
     */
    public ?string $url = null;

    /**
     * Метатеги.
     *
     * @var ?Metatag
     */
    public ?Metatag $metatag = null;

    /**
     * Элементы.
     *
     * @var ?array<int, SectionItem>
     */
    public ?array $items = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $metatag_id ID метатегов.
     * @param string|null $name Название.
     * @param string|null $header Заголовок.
     * @param string|null $text Статья.
     * @param string|null $additional Дополнительное описание.
     * @param Level|null $level Уровень.
     * @param bool|null $free Признак бесплатности.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param string|null $url URL на раздел.
     * @param Metatag|null $metatag Метатеги.
     * @param array<int, SectionItem>|null $items Элементы.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $metatag_id = null,
        ?string         $name = null,
        ?string         $header = null,
        ?string         $text = null,
        ?string         $additional = null,
        ?Level          $level = null,
        ?bool           $free = null,
        ?bool           $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?string         $url = null,
        ?Metatag        $metatag = null,
        ?array          $items = null,
    )
    {
        $this->id = $id;
        $this->metatag_id = $metatag_id;
        $this->name = $name;
        $this->header = $header;
        $this->text = $text;
        $this->additional = $additional;
        $this->level = $level;
        $this->free = $free;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->url = $url;
        $this->metatag = $metatag;
        $this->items = $items;
    }
}
