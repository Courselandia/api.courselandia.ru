<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Models;

use App\Modules\Document\Contracts\DocumentDriver;
use Config;
use CURLFile;
use File;
use App\Models\Exceptions\CurlException;

/**
 * Класс драйвер хранения документов с использованием HTTP протокола.
 */
class DocumentDriverHttp extends DocumentDriver
{
    /**
     * Метод получения пути к документу.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет путь к документу.
     */
    public function path(string $folder, int|string $id, string $format): ?string
    {
        return Config::get('document.store.http.read').$folder.'/'.$id.'.'.$format;
    }

    /**
     * Метод получения физического пути к документу.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет физический путь к документу.
     */
    public function pathSource(string $folder, int|string $id, string $format): ?string
    {
        return $this->path($folder, $id, $format);
    }

    /**
     * Метод чтения документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет байт код документа.
     */
    public function read(string $folder, int|string $id, string $format): ?string
    {
        return null;
    }

    /**
     * Метод создания документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     * @param  string  $path  Путь к документу.
     *
     * @return bool Вернет статус успешности создания документа.
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

        curl_setopt($ch, CURLOPT_URL, Config::get('document.store.http.create'));
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
     * Метод обновления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     * @param  string  $path  Путь к документу.
     *
     * @return bool Вернет статус успешности обновления документа.
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

        curl_setopt($ch, CURLOPT_URL, Config::get('document.store.http.update'));
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
     * Метод удаления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return bool Вернет статус успешности удаления документа.
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

        curl_setopt($ch, CURLOPT_URL, Config::get('document.store.http.destroy'));
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
