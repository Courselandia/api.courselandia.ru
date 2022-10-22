<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Models;

use App\Modules\Image\Contracts\ImageDriver;
use Config;
use File;

/**
 * Класс драйвер хранения изображений в локальной папке.
 */
class ImageDriverLocal extends ImageDriver
{
    /**
     * Метод получения пути к изображению.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет путь к изображению.
     */
    public function path(string $folder, int|string $id, string $format): ?string
    {
        return Config::get('image.store.local.path').$folder.'/'.$id.'.'.$format;
    }

    /**
     * Метод получения физического пути к изображению.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет физический путь к изображению.
     */
    public function pathSource(string $folder, int|string $id, string $format): ?string
    {
        return Config::get('image.store.local.pathSource').$folder.'/'.$id.'.'.$format;
    }

    /**
     * Метод чтения изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет байт код изображения.
     */
    public function read(string $folder, int|string $id, string $format): ?string
    {
        return null;
    }

    /**
     * Метод создания изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     * @param  string  $path  Путь к изображению.
     *
     * @return bool Вернет статус успешности создания изображения.
     */
    public function create(string $folder, int|string $id, string $format, string $path): bool
    {
        $newPath = $this->pathSource($folder, $id, $format);
        $pathDir = dirname($newPath);

        if (!File::exists($pathDir)) {
            File::makeDirectory($pathDir, 493, true);
        }

        return File::copy($path, $newPath);
    }

    /**
     * Метод обновления изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     * @param  string  $path  Путь к изображению.
     *
     * @return bool Вернет статус успешности обновления изображения.
     */
    public function update(string $folder, int|string $id, string $format, string $path): bool
    {
        $newPath = $this->pathSource($folder, $id, $format);
        $pathDir = dirname($newPath);

        if (!File::exists($pathDir)) {
            File::makeDirectory($pathDir, 493, true);
        }

        return File::copy($path, $newPath);
    }

    /**
     * Метод удаления изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return bool Вернет статус успешности удаления изображения.
     */
    public function destroy(string $folder, int|string $id, string $format): bool
    {
        return File::delete($this->pathSource($folder, $id, $format));
    }
}
