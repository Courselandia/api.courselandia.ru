<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Values;

use App\Models\Value;

/**
 * Объект-значение для разбивки отзывов.
 */
class ReviewBreakDown extends Value
{
    /**
     * Рейтинг.
     *
     * @var int
     */
    private int $rating;

    /**
     * Количество.
     *
     * @var int
     */
    private int $amount;

    /**
     * @param int $rating Рейтинг.
     * @param int $amount Количество.
     */
    public function __construct(int $rating, int $amount)
    {
        $this->rating = $rating;
        $this->amount = $amount;
    }

    /**
     * Получить рейтинг.
     *
     * @return int Рейтинг.
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * Получить количество.
     *
     * @return int Количество.
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
}
