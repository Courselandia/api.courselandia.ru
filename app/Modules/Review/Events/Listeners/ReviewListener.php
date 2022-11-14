<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Events\Listeners;

use App\Models\Exceptions\RecordExistException;
use App\Modules\Review\Models\Review;

/**
 * Класс обработчик событий для модели отзывов.
 */
class ReviewListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param  Review  $review  Модель для таблицы отзывов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function creating(Review $review): bool
    {
        $result = $review->newQuery()
            ->where('school_id', $review->school_id)
            ->where('course_id', $review->course_id)
            ->where('name', $review->name)
            ->where('title', $review->title)
            ->where('text', $review->text)
            ->where('rating', $review->rating)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('review::events.listeners.reviewListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param  Review  $review  Модель для таблицы отзывов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function updating(Review $review): bool
    {
        $result = $review->newQuery()
            ->where('id', '!=', $review->id)
            ->where('school_id', $review->school_id)
            ->where('course_id', $review->course_id)
            ->where('name', $review->name)
            ->where('title', $review->title)
            ->where('text', $review->text)
            ->where('rating', $review->rating)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('review::events.listeners.reviewListener.existError'));
        }

        return true;
    }
}
