<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Events\Listeners;

use App\Models\Exceptions\RecordExistException;
use App\Modules\Course\Models\Course;

/**
 * Класс обработчик событий для модели курсов.
 */
class CourseListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param Course $course Модель для таблицы курсов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function creating(Course $course): bool
    {
        $result = $course->newQuery()
            ->where('link', $course->link)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('course::events.listeners.courseListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param Course $course Модель для таблицы курсов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function updating(Course $course): bool
    {
        $result = $course->newQuery()
            ->where('id', '!=', $course->id)
            ->where('link', $course->link)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('course::events.listeners.courseListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при удалении записи.
     *
     * @param Course $course Модель для таблицы курсов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Course $course): bool
    {
        $course->deleteRelation($course->levels(), $course->isForceDeleting());
        $course->deleteRelation($course->learns(), $course->isForceDeleting());
        $course->deleteRelation($course->employments(), $course->isForceDeleting());
        $course->deleteRelation($course->features(), $course->isForceDeleting());

        $course->directions()->detach();
        $course->professions()->detach();
        $course->categories()->detach();
        $course->skills()->detach();
        $course->teachers()->detach();
        $course->tools()->detach();

        return true;
    }
}
