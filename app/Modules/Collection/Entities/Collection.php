<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Entities;

use Carbon\Carbon;
use App\Models\Entity;
use App\Modules\Analyzer\Entities\Analyzer;
use App\Modules\Course\Entities\Course;
use App\Modules\Direction\Entities\Direction;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Сущность для коллекции.
 */
class Collection extends Entity
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
     * Статья.
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
     * Изображение маленькое.
     *
     * @var ?Image
     */
    public ?Image $image_small_id = null;

    /**
     * Изображение среднее.
     *
     * @var ?Image
     */
    public ?Image $image_middle_id = null;

    /**
     * Изображение большое.
     *
     * @var ?Image
     */
    public ?Image $image_big_id = null;

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
     * Метатеги.
     *
     * @var ?Metatag
     */
    public ?Metatag $metatag = null;

    /**
     * Направление.
     *
     * @var ?Direction
     */
    public ?Direction $direction = null;

    /**
     * Фильтры.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CollectionFilter::class)]
    public ?DataCollection $filters = null;

    /**
     * Курсы коллекции.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Course::class)]
    public ?DataCollection $courses = null;

    /**
     * Анализ хранения текстов.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Analyzer::class)]
    public ?DataCollection $analyzers = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $metatag_id ID метатегов.
     * @param int|string|null $direction_id ID направления.
     * @param string|null $name Название.
     * @param string|null $link Ссылка.
     * @param string|null $text Статья.
     * @param string|null $additional Дополнительное описание.
     * @param int|null $amount Количество курсов.
     * @param string|null $sort_field Поле сортировки.
     * @param string|null $sort_direction Направление сортировки.
     * @param Image|null $image_small_id Изображение маленькое.
     * @param Image|null $image_middle_id Изображение среднее.
     * @param Image|null $image_big_id Изображение большое.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param Direction|null $direction Направление.
     * @param Metatag|null $metatag Метатеги.
     * @param DataCollection|null $filters Фильтры.
     * @param DataCollection|null $courses Курсы коллекции.
     * @param DataCollection|null $analyzers Анализ хранения текстов.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $metatag_id = null,
        int|string|null $direction_id = null,
        ?string         $name = null,
        ?string         $link = null,
        ?string         $text = null,
        ?string         $additional = null,
        ?int            $amount = null,
        ?string         $sort_field = null,
        ?string         $sort_direction = null,
        ?Image          $image_small_id = null,
        ?Image          $image_middle_id = null,
        ?Image          $image_big_id = null,
        ?bool           $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?Direction      $direction = null,
        ?Metatag        $metatag = null,
        ?DataCollection $filters = null,
        ?DataCollection $courses = null,
        ?DataCollection $analyzers = null,
    )
    {
        $this->id = $id;
        $this->metatag_id = $metatag_id;
        $this->direction_id = $direction_id;
        $this->name = $name;
        $this->link = $link;
        $this->text = $text;
        $this->additional = $additional;
        $this->amount = $amount;
        $this->sort_field = $sort_field;
        $this->sort_direction = $sort_direction;
        $this->image_small_id = $image_small_id;
        $this->image_middle_id = $image_middle_id;
        $this->image_big_id = $image_big_id;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->direction = $direction;
        $this->metatag = $metatag;
        $this->filters = $filters;
        $this->courses = $courses;
        $this->analyzers = $analyzers;
    }
}
