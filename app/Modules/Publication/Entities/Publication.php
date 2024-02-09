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
     * @var ?Image
     */
    public ?Image $image_small_id = null;

    /**
     * Среднее изображение.
     *
     * @var ?Image
     */
    public ?Image $image_middle_id = null;

    /**
     * Большое изображение.
     *
     * @var ?Image
     */
    public ?Image $image_big_id = null;

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

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $metatag_id ID метатегов.
     * @param Carbon|null $published_at Дата добавления.
     * @param string|null $header Заголовок.
     * @param string|null $link Ссылка.
     * @param string|null $anons Анонс.
     * @param string|null $article Статья.
     * @param Image|null $image_small_id Маленькое изображение.
     * @param Image|null $image_middle_id Среднее изображение.
     * @param Image|null $image_big_id Большое изображение.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param Metatag|null $metatag Метатеги.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $metatag_id = null,
        ?Carbon         $published_at = null,
        ?string         $header = null,
        ?string         $link = null,
        ?string         $anons = null,
        ?string         $article = null,
        ?Image          $image_small_id = null,
        ?Image          $image_middle_id = null,
        ?Image          $image_big_id = null,
        ?bool           $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?Metatag        $metatag = null,
    )
    {
        $this->id = $id;
        $this->metatag_id = $metatag_id;
        $this->published_at = $published_at;
        $this->header = $header;
        $this->link = $link;
        $this->anons = $anons;
        $this->article = $article;
        $this->image_small_id = $image_small_id;
        $this->image_middle_id = $image_middle_id;
        $this->image_big_id = $image_big_id;
        $this->metatag = $metatag;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
