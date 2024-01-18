<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Entities;

use App\Models\EntityNew;

/**
 * Сущность для документа.
 */
class Document extends EntityNew
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

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $byte Байткод.
     * @param string|null $folder Папка хранения файла.
     * @param string|null $format Папка хранения файла.
     * @param string|null $cache Постфикс для кеширования.
     * @param string|null $path Путь к файлу.
     * @param string|null $pathCache Путь к файлу с кешированием.
     * @param string|null $pathSource Путь фактического месторасположения файла.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $byte = null,
        ?string         $folder = null,
        ?string         $format = null,
        ?string         $cache = null,
        ?string         $path = null,
        ?string         $pathCache = null,
        ?string         $pathSource = null
    )
    {
        $this->id = $id;
        $this->byte = $byte;
        $this->folder = $folder;
        $this->format = $format;
        $this->cache = $cache;
        $this->path = $path;
        $this->pathCache = $pathCache;
        $this->pathSource = $pathSource;
    }
}
