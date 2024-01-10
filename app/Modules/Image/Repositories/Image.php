<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Repositories;

use Config;
use Exception;
use File;
use Generator;
use App\Models\Repository;
use App\Models\Exceptions\InvalidFormatException;
use App\Modules\Image\Entities\Image as ImageEntity;

/**
 * Абстрактный класс построения репозитория.
 */
abstract class Image extends Repository
{
    /**
     * Полученные раннее изображения.
     * Будем хранить данные полученных ранее изображений, чтобы снизить нагрузку на систему.
     *
     * @var array
     */
    private static array $images = [];

    /**
     * Папка для хранения.
     *
     * @var string
     */
    private string $folder;

    /**
     * Получение изображения по ее ID из базы ранее полученных изображений.
     *
     * @param int|string $id ID изображения.
     *
     * @return ImageEntity|null Сущность изображения.
     */
    protected static function getById(int|string $id): ?ImageEntity
    {
        return self::$images[$id] ?? null;
    }

    /**
     * Установка данных изображения по ее ID в базу ранее полученных изображений.
     *
     * @param int|string $id ID изображения.
     * @param ImageEntity $image Сущность изображения.
     *
     * @return void
     */
    protected static function setById(int|string $id, ImageEntity $image): void
    {
        self::$images[$id] = $image;
    }

    /**
     * Получение всех записей.
     *
     * @param ImageEntity|null $entity Сущность.
     *
     * @return Generator|ImageEntity|null Генератор.
     */
    abstract public function all(ImageEntity $entity = null): Generator|ImageEntity|null;

    /**
     * Получить количество всех изображений.
     *
     * @return int Количество записей.
     */
    abstract public function count(): int;

    /**
     * Создание.
     *
     * @param ImageEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID последней вставленной строки.
     */
    abstract public function create(ImageEntity $entity): int|string;

    /**
     * Обновление.
     *
     * @param int|string $id Id записи для обновления.
     * @param ImageEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID вставленной строки.
     */
    abstract public function update(int|string $id, ImageEntity $entity): int|string;

    /**
     * Обновление байт кода картинки.
     *
     * @param int|string $id Id записи для обновления.
     * @param string $byte Байт код картинки.
     *
     * @return bool Вернет булево значение успешности операции.
     */
    abstract public function updateByte(int|string $id, string $byte): bool;

    /**
     * Удаление.
     *
     * @param int|string|array|null $id Id записи для удаления.
     *
     * @return bool Вернет булево значение успешности операции.
     */
    abstract public function destroy(int|string|array $id = null): bool;

    /**
     * Создание копии изображения.
     * Копия создается во временной папке с псевдослучайным названием.
     *
     * @param string $path Путь к изображению из которого нужно сделать копию.
     *
     * @return string Возвращает путь к копии.
     * @throws Exception
     */
    public function copy(string $path): string
    {
        $pro = getImageSize($path);
        $name = $this->tmp($pro[2]);
        File::copy($path, $name);

        return $name;
    }

    /**
     * Производит конвертирования изображения из одного формата в другой.
     *
     * @param string $path Путь к изображению.
     * @param string $formatTo Новый формат для изображения.
     *
     * @return string Возвращает путь к новому изображению.
     * @throws Exception
     */
    public function convertTo(string $path, string $formatTo): string
    {
        if ($this->isImage($path) === true) {
            $name = $this->tmp($formatTo);
            File::put($name, File::get($path));

            return $name;
        }

        throw new InvalidFormatException('The file is not an image.');
    }

    /**
     * Проверяет растровое ли изображение, с которым может работать библиотека GD2.
     *
     * @param string $path Путь к изображению.
     *
     * @return bool Возвращает true если изображение растровое.
     */
    public function isRasterGt(string $path): bool
    {
        $pro = getImageSize($path);
        $format = $pro[2];

        return $format >= 1 && $format <= 3;
    }

    /**
     * Проверка векторное ли изображение.
     *
     * @param string $path Путь к изображению.
     *
     * @return bool Возвращает true если изображение векторное.
     */
    public function isVektor(string $path): bool
    {
        $pro = getImageSize($path);
        $format = $pro[2];

        return $format == 4 || $format == 13;
    }

    /**
     * Проверка является ли файл изображением.
     *
     * @param string $path Путь к изображению.
     *
     * @return bool Возвращает true если файл изображение.
     */
    public function isImage(string $path): bool
    {
        return $this->isRasterGt($path) == true || $this->isVektor($path) == true;
    }

    /**
     * Проверка является ли расширение изображением.
     *
     * @param string $extension Расширение без точки.
     *
     * @return bool Возвращает true если расширение относиться к изображению.
     */
    public function isImageByExtension(string $extension): bool
    {
        return $this->isRastorGtByExtension($extension) || $this->isVektorByExtension($extension);
    }

    /**
     * Проверка является ли расширение растровым.
     *
     * @param string $extension Расширение без точки.
     *
     * @return bool Возвращает true если расширение растровое.
     */
    public function isRastorGtByExtension(string $extension): bool
    {
        return in_array($extension, array('jpg', 'jpeg', 'gif', 'png', 'webp'));
    }

    /**
     * Проверка является ли расширение векторным.
     *
     * @param string $extension Расширение без точки.
     *
     * @return bool Возвращает true если расширение векторное.
     */
    public function isVektorByExtension(string $extension): bool
    {
        return in_array($extension, array('swf', 'flw'));
    }

    /**
     * Переводит нумерованный формат в текстовый формат.
     *
     * @param int $format Нумерованный формат.
     *
     * @return string|null Текстовый формат.
     */
    public function getFormatText(int $format): ?string
    {
        return match ($format) {
            1 => 'gif',
            2 => 'jpg',
            3 => 'png',
            13, 4 => 'swf',
            5 => 'psd',
            6 => 'bmp',
            8, 7 => 'tiff',
            9 => 'jpc',
            10 => 'jp2',
            11 => 'jpx',
            18 => 'webp',
            default => null,
        };
    }

    /**
     * Получение пути к файлу для временного изображения.
     *
     * @param string|int $format Формат изображения в нумерованном виде или текстовом.
     *
     * @return string Путь к временному изображению.
     * @throws Exception
     */
    public function tmp(string|int $format): string
    {
        $format = is_numeric($format) ? $this->getFormatText($format) : $format;

        if ($format) {
            return storage_path('app/tmp/img_' . time() . mt_rand(1, 100000) . '.' . $format);
        }

        throw new InvalidFormatException('The *.' . $format . ' format is not allowed.');
    }

    /**
     * Установка папки хранения.
     *
     * @param string $folder Название папки.
     *
     * @return $this Вернет текущий объект.
     */
    public function setFolder(string $folder): Image
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Получение папки хранения.
     *
     * @return string Вернет название папки.
     */
    public function getFolder(): string
    {
        return Config::get('app.env') === 'testing' ? 'test/' . $this->folder : $this->folder;
    }
}
