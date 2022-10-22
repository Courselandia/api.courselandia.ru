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
use File;

/**
 * Класс драйвер хранения документов в локальной папке.
 */
class DocumentDriverLocal extends DocumentDriver
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
        return Config::get('document.store.local.path').$folder.'/'.$id.'.'.$format;
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
        return Config::get('document.store.local.pathSource').$folder.'/'.$id.'.'.$format;
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
     * Метод обновления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     * @param  string  $path  Путь к документу.
     *
     * @return bool Вернет статус успешности обновления документа.
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
     * Метод удаления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return bool Вернет статус успешности удаления документа.
     */
    public function destroy(string $folder, int|string $id, string $format): bool
    {
        return File::delete($this->pathSource($folder, $id, $format));
    }
}
