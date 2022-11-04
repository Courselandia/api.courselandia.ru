<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Events\Listeners;

use App\Models\Exceptions\RecordExistException;
use App\Modules\Faq\Models\Faq;

/**
 * Класс обработчик событий для модели FAQ.
 */
class FaqListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param  Faq  $faq  Модель для таблицы FAQ.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function creating(Faq $faq): bool
    {
        $result = $faq->newQuery()
            ->where('school_id', $faq->school_id)
            ->where('question', $faq->question)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('faq::events.listeners.faqListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param  Faq  $faq  Модель для таблицы FAQ.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function updating(Faq $faq): bool
    {
        $result = $faq->newQuery()
            ->where('id', '!=', $faq->id)
            ->where('school_id', $faq->school_id)
            ->where('question', $faq->question)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('faq::events.listeners.faqListener.existError'));
        }

        return true;
    }
}
