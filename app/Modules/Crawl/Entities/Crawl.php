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
     * ID задачи на индексацию.
     *
     * @var string|null
     */
    public ?string $task_id = null;

    /**
     * Дата отправки на индексацию.
     *
     * @var ?Carbon
     */
    public ?Carbon $pushed_at = null;

    /**
     * Дата индексации.
     *
     * @var ?Carbon
     */
    public ?Carbon $crawled_at = null;

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
}
