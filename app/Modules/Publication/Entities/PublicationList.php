<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Entities;

use App\Models\Entity;
use App\Modules\Publication\Values\PublicationYear;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

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
     * @var int
     */
    public int $total = 0;

    /**
     * Год.
     *
     * @var int|null
     */
    public ?int $year = null;

    /**
     * Публикации.
     *
     * @var DataCollection
     */
    #[DataCollectionOf(Publication::class)]
    public DataCollection $publications;

    /**
     * @param DataCollection $publications Публикации.
     * @param int $total Количество записей.
     * @param array|null $years Года.
     * @param int|null $year Год.
     */
    public function __construct(
        DataCollection $publications,
        int            $total = 0,
        ?array         $years = null,
        ?int           $year = null,
    )
    {
        $this->publications = $publications;
        $this->total = $total;
        $this->years = $years;
        $this->year = $year;
    }
}
