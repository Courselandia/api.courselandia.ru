<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Contracts;

/**
 * Абстрактный класс позволяющий проектировать собственные классы для хранения документов.
 */
abstract class DocumentDriver
{
    /**
     * Абстрактный метод получения пути к документу.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет путь к документу.
     */
    abstract public function path(string $folder, int|string $id, string $format): ?string;

    /**
     * Абстрактный метод получения физического пути к документу.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет физический путь к документу.
     */
    abstract public function pathSource(string $folder, int|string $id, string $format): ?string;

    /**
     * Абстрактный метод чтения документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return string|null Вернет байт код документа.
     */
    abstract public function read(string $folder, int|string $id, string $format): ?string;

    /**
     * Абстрактный метод создания документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     * @param  string  $path  Путь к документу.
     *
     * @return bool Вернет статус успешности создания документа.
     */
    abstract public function create(string $folder, int|string $id, string $format, string $path): bool;

    /**
     * Абстрактный метод обновления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     * @param  string  $path  Путь к документу.
     *
     * @return bool Вернет статус успешности обновления документа.
     */
    abstract public function update(string $folder, int|string $id, string $format, string $path): bool;

    /**
     * Абстрактный метод удаления документа.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор документа.
     * @param  string  $format  Формат документа.
     *
     * @return bool Вернет статус успешности удаления документа.
     */
    abstract public function destroy(string $folder, int|string $id, string $format): bool;
}
