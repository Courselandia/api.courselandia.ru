<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Data\Actions\Admin;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

/**
 * Данные для обновления публикации.
 */
class PublicationUpdate extends PublicationCreate
{
    /**
     * ID публикации.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID публикации.
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
        int|string    $id,
        ?Carbon       $published_at = null,
        ?string       $header = null,
        ?string       $link = null,
        ?string       $anons = null,
        ?string       $article = null,
        ?UploadedFile $image = null,
        ?bool         $status = null,
        ?string       $description = null,
        ?string       $keywords = null,
        ?string       $title = null
    )
    {
        $this->id = $id;

        parent::__construct(
            $published_at,
            $header,
            $link,
            $anons,
            $article,
            $image,
            $status,
            $description,
            $keywords,
            $title,
        );
    }
}
