<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Contracts;

/**
 * Абстрактный класс позволяющий проектировать собственные классы для хранения изображений.
 */
abstract class ImageDriver
{
    /**
     * Абстрактный метод получения пути к изображению.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет путь к изображению.
     */
    abstract public function path(string $folder, int|string $id, string $format): ?string;

    /**
     * Абстрактный метод получения физического пути к изображению.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет физический путь к изображению.
     */
    abstract public function pathSource(string $folder, int|string $id, string $format): ?string;

    /**
     * Абстрактный метод чтения изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return string|null Вернет байт код изображения.
     */
    abstract public function read(string $folder, int|string $id, string $format): ?string;

    /**
     * Абстрактный метод создания изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     * @param  string  $path  Путь к изображению.
     *
     * @return bool Вернет статус успешности создания изображения.
     */
    abstract public function create(string $folder, int|string $id, string $format, string $path): bool;

    /**
     * Абстрактный метод обновления изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     * @param  string  $path  Путь к изображению.
     *
     * @return bool Вернет статус успешности обновления изображения.
     */
    abstract public function update(string $folder, int|string $id, string $format, string $path): bool;

    /**
     * Абстрактный метод удаления изображения.
     *
     * @param  string  $folder  Папка.
     * @param  int|string  $id  Идентификатор изображения.
     * @param  string  $format  Формат изображения.
     *
     * @return bool Вернет статус успешности удаления изображения.
     */
    abstract public function destroy(string $folder, int|string $id, string $format): bool;
}
