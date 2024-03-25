<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Data;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\DataCollection;

/**
 * Данные для обновления коллекции.
 */
class CollectionUpdate extends CollectionCreate
{
    /**
     * ID коллекции.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID коллекции.
     * @param int|string|null $direction_id ID направления.
     * @param string|null $name Название.
     * @param string|null $link Ссылка.
     * @param string|null $text Текст.
     * @param string|null $additional Дополнительное описание.
     * @param int|null $amount Количество курсов.
     * @param string|null $sort_field Поле сортировки.
     * @param string|null $sort_direction Направление сортировки.
     * @param UploadedFile|null $image Изображение.
     * @param bool|null $status Статус.
     * @param string|null $title Заголовок.
     * @param string|null $description Описания.
     * @param string|null $keywords Ключевые слова.
     * @param ?DataCollection $filters Фильтры.
     */
    public function __construct(
        int|string        $id,
        int|string|null   $direction_id = null,
        ?string           $name = null,
        ?string           $link = null,
        ?string           $text = null,
        ?string           $additional = null,
        ?int              $amount = null,
        ?string           $sort_field = null,
        ?string           $sort_direction = null,
        UploadedFile|null $image = null,
        ?bool             $status = null,
        ?string           $title = null,
        ?string           $description = null,
        ?string           $keywords = null,
        ?DataCollection   $filters = null,
    )
    {
        $this->id = $id;

        parent::__construct(
            $direction_id,
            $name,
            $link,
            $text,
            $additional,
            $amount,
            $sort_field,
            $sort_direction,
            $image,
            $status,
            $description,
            $keywords,
            $title,
            $filters,
        );
    }
}
