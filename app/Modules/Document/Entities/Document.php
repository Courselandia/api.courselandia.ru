<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use Str;

/**
 * Сущность для документа.
 */
class Document extends Entity
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