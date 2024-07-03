<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Normalize\Workers;

use App\Modules\Image\Normalize\Worker;
use App\Modules\Publication\Models\Publication;
use Illuminate\Database\Eloquent\Builder;

/**
 * Нормализация для изображений публикаций.
 */
class PublicationWorker extends Worker
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
        $publications = $this->getQuery()->get();

        foreach ($publications as $publication) {
            $imageSmall = $publication->image_small_id ? json_encode($publication->image_small_id) : null;
            $imageMiddle = $publication->image_middle_id ? json_encode($publication->image_middle_id) : null;
            $imageBig = $publication->image_big_id ? json_encode($publication->image_big_id) : null;

            if (
                $imageSmall !== $publication->image_small
                || $imageMiddle !== $publication->image_middle
                || $imageBig !== $publication->image_big
            ) {
                $publication->image_small = $imageSmall;
                $publication->image_middle = $imageMiddle;
                $publication->image_big = $imageBig;

                $publication->save();
            }

            $this->fireEvent('normalized', [$publication]);
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Publication::active()
            ->orderBy('published_at', 'DESC')
            ->orderBy('id', 'DESC');
    }
}