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
}
