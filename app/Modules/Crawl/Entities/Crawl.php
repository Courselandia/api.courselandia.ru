<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use App\Modules\Page\Entities\Page;
use App\Modules\Crawl\Enums\Engine;

/**
 * Сущность для направлений.
 */
class Crawl extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID страницы.
     *
     * @var int|string|null
     */
    public int|string|null $page_id = null;

    /**
     * Дата отправки на индексацию.
     *
     * @var ?Carbon
     */
    public ?Carbon $pushed_at = null;

    /**
     * Поисковая система.
     *
     * @var ?Engine
     */
    public ?Engine $engine = null;

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
     * Страница.
     *
     * @var ?Page
     */
    public ?Page $page = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $page_id ID страницы.
     * @param Carbon|null $pushed_at Дата отправки на индексацию.
     * @param Engine|null $engine Поисковая система.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param Page|null $page Страница.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $page_id = null,
        ?Carbon         $pushed_at = null,
        ?Engine         $engine = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?Page           $page = null
    )
    {
        $this->id = $id;
        $this->page_id = $page_id;
        $this->pushed_at = $pushed_at;
        $this->engine = $engine;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->page = $page;
    }
}
