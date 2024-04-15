<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Data;

use App\Models\Data;

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
     * @var ?array<int, CollectionFilter>
     */
    public ?array $filters = null;

    /**
     * Признак того, что нам нужно получить только количество курсов.
     *
     * @var bool
     */
    public bool $onlyCount = false;

    /**
     * @param ?array<int, CollectionFilter> $filters Фильтры.
     * @param int|null $limit Количество получаемых курсов.
     * @param array|null $sorts Сортировка данных.
     * @param bool $onlyCount Сортировка данных.
     */
    public function __construct(
        ?array     $filters = null,
        ?int       $limit = null,
        array|null $sorts = null,
        bool       $onlyCount = false,
    )
    {
        $this->limit = $limit;
        $this->filters = $filters;
        $this->sorts = $sorts;
        $this->onlyCount = $onlyCount;
    }
}
