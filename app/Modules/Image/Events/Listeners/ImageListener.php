<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Events\Listeners;

use Eloquent;
use App;
use Config;

/**
 * Класс обработчик событий для модели изображений.
 */
class ImageListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param  Eloquent  $image  Модель изображений.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function created(Eloquent $image): bool
    {
        return App::make('image.store.driver')->create($image->folder, $image->id, $image->format, $image->path);
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param  Eloquent  $image  Модель изображений.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function updated(Eloquent $image): bool
    {
        $original = $image->getRawOriginal();
        $id = $original['_id'] ?? $original['id'];
        App::make('image.store.driver')->destroy($original['folder'], $id, $original['format']);

        return App::make('image.store.driver')->update($image->folder, $image->id, $image->format, $image->path);
    }

    /**
     * Обработчик события при чтении данных.
     *
     * @param  Eloquent  $image  Модель изображений.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function readed(Eloquent $image): bool
    {
        $image->path = Config::get('app.api_url') . '/' . App::make('image.store.driver')->path(
                $image->folder,
                $image->id,
                $image->format
            );
        $image->pathCache = $image->path;
        $image->byte = App::make('image.store.driver')->read($image->folder, $image->id, $image->format);

        if ($image->cache) {
            $image->path .= '?'.$image->cache;
        }

        $image->pathSource = App::make('image.store.driver')->pathSource($image->folder, $image->id, $image->format);

        return true;
    }

    /**
     * Обработчик события при удалении записи.
     *
     * @param  Eloquent  $image  Модель изображений.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleted(Eloquent $image): bool
    {
        if (!Config::get('image.soft_deletes')) {
            return App::make('image.store.driver')->destroy($image->folder, $image->id, $image->format);
        }

        return true;
    }
}
