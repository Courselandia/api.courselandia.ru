<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Entities;

use App\Models\Entity;

/**
 * Сущность для списка публикаций.
 */
class PublicationList extends Entity
{
    /**
     * Года.
     *
     * @var PublicationYear[]|null
     */
    public ?array $years = null;

    /**
     * Количество записей.
     *
     * @var int|null
     */
    public ?int $total = null;

    /**
     * Публикации.
     *
     * @var Publication[]|null
     */
    public ?array $publications = null;

    /**
     * Страница.
     *
     * @var int|null
     */
    public ?int $page = null;

    /**
     * Год.
     *
     * @var int|null
     */
    public ?int $year = null;
}