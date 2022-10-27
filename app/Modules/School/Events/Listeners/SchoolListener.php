<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Events\Listeners;

use ImageStore;
use App\Modules\School\Models\School;

/**
 * Класс обработчик событий для модели школ.
 */
class SchoolListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param  School  $school  Модель для таблицы школ.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(School $school): bool
    {
        if ($school->image_logo_id) {
            ImageStore::destroy($school->image_logo_id->id);
        }
        if ($school->image_site_id) {
            ImageStore::destroy($school->image_site_id->id);
        }

        $school->deleteRelation($school->metatag(), $school->isForceDeleting());

        return true;
    }
}
