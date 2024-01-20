<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Data\Actions\Admin;

use App\Models\Data;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

/**
 * Данные для создания публикации.
 */
class PublicationCreate extends Data
{
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
     * Изображение.
     *
     * @var ?UploadedFile
     */
    public ?UploadedFile $image = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * @param Carbon|null $published_at Дата добавления.
     * @param string|null $header Заголовок.
     * @param string|null $link Ссылка.
     * @param string|null $anons Анонс.
     * @param string|null $article Статья.
     * @param UploadedFile|null $image Изображение.
     * @param bool|null $status Статус.
     * @param string|null $description Описание.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title Заголовок.
     */
    public function __construct(
        ?Carbon $published_at = null,
        ?string $header = null,
        ?string $link = null,
        ?string $anons = null,
        ?string $article = null,
        ?UploadedFile $image = null,
        ?bool $status = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $title = null
    )
    {
        $this->published_at = $published_at;
        $this->header = $header;
        $this->link = $link;
        $this->anons = $anons;
        $this->article = $article;
        $this->image = $image;
        $this->status = $status;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->title = $title;
    }
}
