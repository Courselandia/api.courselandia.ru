<?php
/**
 * Модуль Страницы.
 * Этот модуль содержит все классы для работы со списком страниц.
 *
 * @package App\Modules\Page
 */

namespace App\Modules\Page\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для страницы.
 */
class Page extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Путь к странице.
     *
     * @var string|null
     */
    public ?string $path = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $lastmod = null;

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
     * @param string|null $path Путь к странице.
     * @param Carbon|null $lastmod Дата обновления.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $path = null,
        ?Carbon         $lastmod = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null
    )
    {
        $this->id = $id;
        $this->path = $path;
        $this->lastmod = $lastmod;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
