<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Events\Listeners;

use ImageStore;
use App\Modules\Teacher\Models\Teacher;

/**
 * Класс обработчик событий для модели учителя.
 */
class TeacherListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param Teacher $teacher Модель для таблицы учителя.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Teacher $teacher): bool
    {
        if ($teacher->image_small_id) {
            ImageStore::destroy($teacher->image_small_id->id);
        }

        if ($teacher->image_middle_id) {
            ImageStore::destroy($teacher->image_middle_id->id);
        }

        if ($teacher->image_big_id) {
            ImageStore::destroy($teacher->image_big_id->id);
        }

        $teacher->deleteRelation($teacher->metatag(), $teacher->isForceDeleting());
        $teacher->deleteRelation($teacher->experiences(), $teacher->isForceDeleting());
        $teacher->deleteRelation($teacher->socialMedias(), $teacher->isForceDeleting());
        $teacher->directions()->detach();
        $teacher->schools()->detach();
        $teacher->courses()->detach();
        $teacher->deleteRelation($teacher->analyzers(), $teacher->isForceDeleting());
        $teacher->deleteRelation($teacher->articles(), $teacher->isForceDeleting());

        return true;
    }
}
