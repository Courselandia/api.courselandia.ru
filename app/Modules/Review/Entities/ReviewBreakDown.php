<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Entities;

use App\Models\Entity;

/**
 * Сущность для разбивки отзывов.
 */
class ReviewBreakDown extends Entity
{
    /**
     * Рейтинг.
     *
     * @var int|null
     */
    public ?int $rating = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $amount = null;
}
