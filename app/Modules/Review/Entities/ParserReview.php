<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для спарсенного отзыва.
 */
class ParserReview extends Entity
{
    /**
     * ID тзыва.
     *
     * @var int|null
     */
    public ?int $id = null;

    /**
     * Имя автора.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Отзыв.
     *
     * @var string|null
     */
    public ?string $review = null;

    /**
     * Достоинства.
     *
     * @var string|null
     */
    public ?string $advantages = null;

    /**
     * Недостатки.
     *
     * @var string|null
     */
    public ?string $disadvantages = null;

    /**
     * Рейтинг.
     *
     * @var int|null
     */
    public ?int $rating = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $date = null;

    /**
     * @param int|null $id ID тзыва.
     * @param string|null $name Имя автора.
     * @param string|null $title Заголовок.
     * @param string|null $review Отзыв.
     * @param string|null $advantages Достоинства.
     * @param string|null $disadvantages Недостатки.
     * @param int|null $rating Рейтинг.
     * @param Carbon|null $date Дата создания.
     */
    public function __construct(
        ?int    $id = null,
        ?string $name = null,
        ?string $title = null,
        ?string $review = null,
        ?string $advantages = null,
        ?string $disadvantages = null,
        ?int    $rating = null,
        Carbon  $date = null
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->review = $review;
        $this->advantages = $advantages;
        $this->disadvantages = $disadvantages;
        $this->rating = $rating;
        $this->date = $date;
    }
}
