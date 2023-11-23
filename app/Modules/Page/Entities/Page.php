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
}
