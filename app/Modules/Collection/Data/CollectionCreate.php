<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Data;

use App\Models\Data;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Данные для создания коллекции.
 */
class CollectionCreate extends Data
{
    /**
     * ID направления.
     *
     * @var int|string|null
     */
    public int|string|null $direction_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Текст.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Дополнительное описание.
     *
     * @var string|null
     */
    public ?string $additional = null;

    /**
     * Количество курсов.
     *
     * @var int|null
     */
    public ?int $amount = null;

    /**
     * Поле сортировки.
     *
     * @var string|null
     */
    public ?string $sort_field = null;

    /**
     * Направление сортировки.
     *
     * @var string|null
     */
    public ?string $sort_direction = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public UploadedFile|null $image = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Описания.
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
     * Фильтры.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CollectionFilterCreate::class)]
    public ?DataCollection $filters = null;

    /**
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
        $this->direction_id = $direction_id;
        $this->name = $name;
        $this->link = $link;
        $this->text = $text;
        $this->additional = $additional;
        $this->amount = $amount;
        $this->sort_field = $sort_field;
        $this->sort_direction = $sort_direction;
        $this->image = $image;
        $this->status = $status;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->title = $title;
        $this->filters = $filters;
    }
}
