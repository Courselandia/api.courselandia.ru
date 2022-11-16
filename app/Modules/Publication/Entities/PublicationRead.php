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
 * Сущность для чтения публикаций.
 */
class PublicationRead extends Entity
{
    /**
     * Год.
     *
     * @var int|null
     */
    public ?int $year = null;

    /**
     * Лимит.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Отступ.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * ID публикации.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Публикации.
     *
     * @var Publication[]|null
     */
    public ?array $publications = null;

    /**
     * Публикация.
     *
     * @var Publication|null
     */
    public ?Publication $publication = null;

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
}
