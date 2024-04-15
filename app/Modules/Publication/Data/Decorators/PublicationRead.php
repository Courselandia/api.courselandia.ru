<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Data\Decorators;

use App\Models\Data;
use App\Modules\Publication\Entities\Publication;

/**
 * Данные для декоратора для чтения публикаций.
 */
class PublicationRead extends Data
{
    /**
     * Год.
     *
     * @var int|null
     */
    public ?int $year = null;

    /**
     * Массив списка доступных годов.
     *
     * @var array|null
     */
    public ?array $years = null;

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
     * Публикация.
     *
     * @var ?Publication
     */
    public ?Publication $publication = null;

    /**
     * Публикации.
     *
     * @var ?array<int, Publication>
     */
    public ?array $publications = null;

    /**
     * Количество записей.
     *
     * @var int|null
     */
    public ?int $total = null;

    /**
     * @param int|null $year Год.
     * @param int|array $years Массив списка доступных годов..
     * @param int|null $limit Лимит.
     * @param int|null $offset Отступ.
     * @param int|string|null $id ID публикации.
     * @param string|null $link Ссылка.
     * @param int|null $total Количество записей..
     * @param ?array<int, Publication> $publications Публикации.
     * @param ?Publication $publication Публикация.
     */
    public function __construct(
        ?int            $year = null,
        ?array          $years = null,
        ?int            $limit = null,
        ?int            $offset = null,
        int|string|null $id = null,
        ?string         $link = null,
        ?int            $total = null,
        ?array          $publications = null,
        ?Publication    $publication = null
    )
    {
        $this->year = $year;
        $this->years = $years;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->id = $id;
        $this->link = $link;
        $this->total = $total;
        $this->publications = $publications;
        $this->publication = $publication;
    }
}
