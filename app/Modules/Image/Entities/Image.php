<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use Str;

/**
 * Сущность для изображения.
 */
class Image extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Байткод.
     *
     * @var string|null
     */
    public ?string $byte = null;

    /**
     * Папка хранения файла.
     *
     * @var string|null
     */
    public ?string $folder = null;

    /**
     * Папка хранения файла.
     *
     * @var string|null
     */
    public ?string $format = null;

    /**
     * Постфикс для кеширования.
     *
     * @var string|null
     */
    public ?string $cache = null;

    /**
     * Ширина изображения.
     *
     * @var int|null
     */
    public ?int $width = null;

    /**
     * Высота изображения.
     *
     * @var int|null
     */
    public ?int $height = null;

    /**
     * Путь к файлу.
     *
     * @var string|null
     */
    public ?string $path = null;

    /**
     * Путь к файлу с кешированием.
     *
     * @var string|null
     */
    public ?string $pathCache = null;

    /**
     * Путь фактического месторасположения файла.
     *
     * @var string|null
     */
    public ?string $pathSource = null;
}