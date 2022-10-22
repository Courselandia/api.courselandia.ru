<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Filters;

use Config;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы обратной связи.
 */
class FeedbackFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int $id ID.
     *
     * @return FeedbackFilter Правила валидации.
     */
    public function id(int $id): FeedbackFilter
    {
        return $this->where('feedbacks.id', $id);
    }

    /**
     * Поиск по дате статьи.
     *
     * @param array $dates Даты от и до.
     *
     * @return FeedbackFilter Правила валидации.
     */
    public function createdAt(array $dates): FeedbackFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('feedbacks.created_at', $dates);
    }

    /**
     * Поиск имени отправителя.
     *
     * @param string $query Строка поиска.
     *
     * @return FeedbackFilter Правила валидации.
     */
    public function name(string $query): FeedbackFilter
    {
        return $this->whereLike('feedbacks.name', $query);
    }

    /**
     * Поиск по e-mail.
     *
     * @param string $query Строка поиска.
     *
     * @return FeedbackFilter Правила валидации.
     */
    public function email(string $query): FeedbackFilter
    {
        return $this->whereLike('feedbacks.email', $query);
    }

    /**
     * Поиск по телефону.
     *
     * @param string $query Строка поиска.
     *
     * @return FeedbackFilter Правила валидации.
     */
    public function phone(string $query): FeedbackFilter
    {
        return $this->whereLike('feedbacks.phone', $query);
    }

    /**
     * Поиск по сообщению.
     *
     * @param string $query Строка поиска.
     *
     * @return FeedbackFilter Правила валидации.
     */
    public function message(string $query): FeedbackFilter
    {
        return $this->whereLike('feedbacks.message', $query);
    }
}
