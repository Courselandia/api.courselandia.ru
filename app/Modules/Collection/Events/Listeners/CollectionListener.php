<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Events\Listeners;

use ImageStore;
use App\Modules\Collection\Models\Collection;

/**
 * Класс обработчик событий для модели коллекции.
 */
class CollectionListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param Collection $collection Модель для таблицы коллекции.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Collection $collection): bool
    {
        if ($collection->image_small_id) {
            ImageStore::destroy($collection->image_small_id->id);
        }

        if ($collection->image_middle_id) {
            ImageStore::destroy($collection->image_middle_id->id);
        }

        if ($collection->image_big_id) {
            ImageStore::destroy($collection->image_big_id->id);
        }

        $collection->deleteRelation($collection->metatag(), $collection->isForceDeleting());
        $collection->deleteRelation($collection->filters(), $collection->isForceDeleting());
        $collection->courses()->detach();
        $collection->deleteRelation($collection->analyzers(), $collection->isForceDeleting());
        $collection->deleteRelation($collection->articles(), $collection->isForceDeleting());

        return true;
    }
}
