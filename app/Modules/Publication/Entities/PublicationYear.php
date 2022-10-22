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
 * Сущность для годов публикаций.
 */
class PublicationYear extends Entity
{
    /**
     * Год.
     *
     * @var int|null
     */
    public ?int $year = null;

    /**
     * Статус текущего года.
     *
     * @var bool|null
     */
    public ?bool $current = null;
}