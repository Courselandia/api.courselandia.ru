<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Events\Listeners;

use Morph;
use ImageStore;
use Typography;
use App\Modules\Course\Enums\Duration;
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
        if ($course->duration_unit === Duration::DAY->value) {
            $course->duration_rate = 1 / 30 * $course->duration;
        } elseif ($course->duration_unit === Duration::WEEK->value) {
            $course->duration_rate = 1 / 4 * $course->duration;
        } elseif ($course->duration_unit === Duration::MONTH->value) {
            $course->duration_rate = $course->duration;
        } elseif ($course->duration_unit === Duration::YEAR->value) {
            $course->duration_rate = 12 * $course->duration;
        }

        $course->name_morphy = Typography::process(Morph::get($course->name), true);
        $course->text_morphy = Typography::process(Morph::get($course->text), true);

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
        if ($course->duration_unit === Duration::DAY->value) {
            $course->duration_rate = 1 / 30 * $course->duration;
        } elseif ($course->duration_unit === Duration::WEEK->value) {
            $course->duration_rate = 1 / 4 * $course->duration;
        } elseif ($course->duration_unit === Duration::MONTH->value) {
            $course->duration_rate = $course->duration;
        } elseif ($course->duration_unit === Duration::YEAR->value) {
            $course->duration_rate = 12 * $course->duration;
        }

        $course->name_morphy = Typography::process(Morph::get($course->name), true);
        $course->text_morphy = Typography::process(Morph::get($course->text), true);

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
        $course->processes()->detach();

        if ($course->image_small_id) {
            ImageStore::destroy($course->image_small_id->id);
        }

        if ($course->image_middle_id) {
            ImageStore::destroy($course->image_middle_id->id);
        }

        if ($course->image_big_id) {
            ImageStore::destroy($course->image_big_id->id);
        }

        return true;
    }
}
