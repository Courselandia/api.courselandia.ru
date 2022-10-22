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
use CURLFile;
use File;
use App\Models\Exceptions\CurlException;

/**
 * Класс драйвер хранения изображений с использованием HTTP протокола.
 */
class ImageDriverHttp extends ImageDriver
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
        return Config::get('image.store.http.read').$folder.'/'.$id.'.'.$format;
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
        return $this->path($folder, $id, $format);
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
     * @throws CurlException;
     */
    public function create(string $folder, int|string $id, string $format, string $path): bool
    {
        $ch = curl_init();
        $tmp = storage_path('app/tmp/'.basename($path));
        File::copy($path, $tmp);

        $data = [
            'id' => $id,
            'format' => $format,
            'file' => new CURLFile($tmp),
            'folder' => $folder
        ];

        curl_setopt($ch, CURLOPT_URL, Config::get('image.store.http.create'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new CurlException($error);
        }

        return true;
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
     * @throws CurlException;
     */
    public function update(string $folder, int|string $id, string $format, string $path): bool
    {
        $ch = curl_init();
        $tmp = storage_path('app/tmp/'.basename($path));
        File::copy($path, $tmp);

        $data = [
            'id' => $id,
            'format' => $format,
            'file' => new CURLFile($tmp),
            'folder' => $folder
        ];

        curl_setopt($ch, CURLOPT_URL, Config::get('image.store.http.update'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new CurlException($error);
        }

        return true;
    }

    /**
     * Метод удаления изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return bool Вернет статус успешности удаления изображения.
     * @throws CurlException;
     */
    public function destroy(string $folder, int|string $id, string $format): bool
    {
        $ch = curl_init();

        $data = [
            'id' => $id,
            'format' => $format,
            'folder' => $folder
        ];

        curl_setopt($ch, CURLOPT_URL, Config::get('image.store.http.destroy'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new CurlException($error);
        }

        return true;
    }
}
