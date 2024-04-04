<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Data;

use App\Models\Data;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Данные для получения курсов через фильтр.
 */
class CollectionCoursesByFilters extends Data
{
    /**
     * Лимит получаемых курсов.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

    /**
     * Фильтры.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CollectionFilter::class)]
    public ?DataCollection $filters = null;

    /**
     * @param ?DataCollection $filters Фильтры.
     * @param int|null $limit Количество получаемых курсов.
     * @param array|null $sorts Сортировка данных.
     */
    public function __construct(?DataCollection $filters = null, ?int $limit = null, array|null $sorts)
    {
        $this->limit = $limit;
        $this->filters = $filters;
        $this->sorts = $sorts;
    }
}
