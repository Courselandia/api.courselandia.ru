<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Models;

use App\Modules\Document\Contracts\DocumentDriver;
use Document as DocumentRepository;
use Config;

/**
 * Класс драйвер хранения документов в базе данных.
 */
class DocumentDriverBase extends DocumentDriver
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
        return 'doc/read/'.$folder.'/'.$id.'.'.$format;
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
        return Config::get('app.api_url').$this->path($folder, $id, $format);
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
        return DocumentRepository::getByte(pathinfo($id.'.'.$format, PATHINFO_FILENAME));
    }

    /**
     * Метод создания документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $path  Путь к документу.
     * @param  string  $format  Формат документа.
     *
     * @return bool Вернет статус успешности создания документа.
     */
    public function create(string $folder, int|string $id, string $format, string $path): bool
    {
        return DocumentRepository::updateByte($id, File::get($path));
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
        return DocumentRepository::updateByte($id, File::get($path));
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
        return true;
    }
}

