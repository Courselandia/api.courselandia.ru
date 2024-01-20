<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Data\Site;

use App\Models\Data;
use App\Modules\Review\Enums\Status;
use Carbon\Carbon;

/**
 * Данные для чтения отзывов.
 */
class ReviewRead extends Data
{
    /**
     * Начало выборки.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки.
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
     * ID школа.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * Ссылка на школу.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Рейтинг для фильтрации.
     *
     * @var int|null
     */
    public ?int $rating = null;

    /**
     * @param int|null $offset Нчало выборки.
     * @param int|null $limit Лимит выборки.
     * @param array|null $sorts Сортировка данных.
     * @param int|null $school_id ID школа.
     * @param string|null $link Ссылка на школу.
     * @param int|null $rating Рейтинг для фильтрации.
     */
    public function __construct(
        ?int    $offset = null,
        ?int    $limit = null,
        ?array  $sorts = null,
        ?int    $school_id = null,
        ?string $link = null,
        ?int    $rating = null
    )
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->sorts = $sorts;
        $this->school_id = $school_id;
        $this->link = $link;
        $this->rating = $rating;
    }
}
