<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;
use Illuminate\Http\UploadedFile;

/**
 * Сущность для школ.
 */
class School extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID метатегов.
     *
     * @var int|string|null
     */
    public int|string|null $metatag_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Сайт.
     *
     * @var string|null
     */
    public ?string $site = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Изображение логотипа.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_logo_id = null;

    /**
     * Изображение сайта.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_site_id = null;

    /**
     * Метатеги.
     *
     * @var Metatag|null
     */
    public ?Metatag $metatag = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;
}
