<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;
use Illuminate\Http\UploadedFile;

/**
 * Сущность для публикаций.
 */
class Publication extends Entity
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
     * Дата добавления.
     *
     * @var ?Carbon
     */
    public ?Carbon $published_at = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Анонс.
     *
     * @var string|null
     */
    public ?string $anons = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $article = null;

    /**
     * Маленькое изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_small_id = null;

    /**
     * Среднее изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_middle_id = null;

    /**
     * Большое изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_big_id = null;

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
