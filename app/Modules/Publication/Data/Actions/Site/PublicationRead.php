<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Data\Actions\Site;

use App\Models\Data;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

/**
 * Данные для чтения публикаций.
 */
class PublicationRead extends Data
{
    /**
     * Год.
     *
     * @var int|null
     */
    public ?int $year = null;

    /**
     * Лимит.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Отступ.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * ID публикации.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * @param int|null $year Год.
     * @param int|null $limit Лимит.
     * @param int|null $offset Отступ.
     * @param int|string|null $id ID публикации.
     * @param string|null $link Ссылка.
     */
    public function __construct(
        ?int            $year = null,
        ?int            $limit = null,
        ?int            $offset = null,
        int|string|null $id = null,
        ?string         $link = null
    )
    {
        $this->year = $year;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->id = $id;
        $this->link = $link;
    }
}
