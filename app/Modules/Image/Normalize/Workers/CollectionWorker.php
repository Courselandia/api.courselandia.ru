<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Normalize\Workers;

use App\Modules\Collection\Models\Collection;
use App\Modules\Image\Normalize\Worker;
use Illuminate\Database\Eloquent\Builder;

/**
 * Нормализация для изображений коллекций.
 */
class CollectionWorker extends Worker
{
    /**
     * Вернет общее количество обрабатываемых записей.
     *
     * @return int Количество записей.
     */
    public function total(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Процесс нормализации.
     *
     * @return void.
     */
    public function run(): void
    {
        $collections = $this->getQuery()->get();

        foreach ($collections as $collection) {
            $imageSmall = $collection->image_small_id ? json_encode($collection->image_small_id) : null;
            $imageMiddle = $collection->image_middle_id ? json_encode($collection->image_middle_id) : null;
            $imageBig = $collection->image_big_id ? json_encode($collection->image_big_id) : null;

            if (
                $imageSmall !== $collection->image_small
                || $imageMiddle !== $collection->image_middle
                || $imageBig !== $collection->image_big
            ) {
                $collection->image_small = $imageSmall;
                $collection->image_middle = $imageMiddle;
                $collection->image_big = $imageBig;

                $collection->save();
            }

            $this->fireEvent('normalized', [$collection]);
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Collection::active()
            ->orderBy('name', 'ASC');
    }
}