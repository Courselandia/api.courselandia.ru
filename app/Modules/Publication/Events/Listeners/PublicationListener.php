<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Events\Listeners;

use ImageStore;
use App\Modules\Publication\Models\Publication;

/**
 * Класс обработчик событий для модели публикаций.
 */
class PublicationListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param Publication $publication Модель для таблицы публикаций.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Publication $publication): bool
    {
        if ($publication->image_small_id) {
            ImageStore::destroy($publication->image_small_id->id);
        }
        if ($publication->image_middle_id) {
            ImageStore::destroy($publication->image_middle_id->id);
        }
        if ($publication->image_big_id) {
            ImageStore::destroy($publication->image_big_id->id);
        }

        $publication->deleteRelation($publication->metatag(), $publication->isForceDeleting());

        return true;
    }
}
