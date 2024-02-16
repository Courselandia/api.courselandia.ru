<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Entities;

use App\Modules\Analyzer\Entities\Analyzer;
use Carbon\Carbon;
use App\Models\Entity;
use App\Modules\Direction\Entities\Direction;
use App\Modules\School\Entities\School;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Сущность для учителя.
 */
class Teacher extends Entity
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
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Город.
     *
     * @var string|null
     */
    public ?string $city = null;

    /**
     * Комментарий.
     *
     * @var string|null
     */
    public ?string $comment = null;

    /**
     * Дополнительное описание.
     *
     * @var string|null
     */
    public ?string $additional = null;

    /**
     * Скопировано.
     *
     * @var bool|null
     */
    public ?bool $copied = null;

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
     * Опции порезанного изображения.
     *
     * @var array|null
     */
    public array|null $image_cropped_options = null;

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
     * Направления.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Direction::class)]
    public ?DataCollection $directions = null;

    /**
     * Школы.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(School::class)]
    public ?DataCollection $schools = null;

    /**
     * Опыт работы учителя.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(TeacherExperience::class)]
    public ?DataCollection $experiences = null;

    /**
     * Социальные сети учителя.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(TeacherSocialMedia::class)]
    public ?DataCollection $social_medias = null;

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
     * @param string|null $name Название.
     * @param string|null $link Ссылка.
     * @param string|null $text Статья.
     * @param float|null $rating Рейтинг.
     * @param string|null $city Город.
     * @param string|null $comment Комментарий.
     * @param string|null $additional Дополнительное описание.
     * @param bool|null $copied Скопировано.
     * @param Image|null $image_small_id Изображение маленькое.
     * @param Image|null $image_middle_id Изображение среднее.
     * @param Image|null $image_big_id Изображение большое.
     * @param array|null $image_cropped_options Опции порезанного изображения.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param Metatag|null $metatag Метатеги.
     * @param DataCollection|null $directions Направления.
     * @param DataCollection|null $schools Школы.
     * @param DataCollection|null $experiences Опыт работы учителя.
     * @param DataCollection|null $social_medias Социальные сети учителя.
     * @param DataCollection|null $analyzers Анализ хранения текстов.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $metatag_id = null,
        ?string         $name = null,
        ?string         $link = null,
        ?string         $text = null,
        ?float          $rating = null,
        ?string         $city = null,
        ?string         $comment = null,
        ?string         $additional = null,
        ?bool           $copied = null,
        ?Image          $image_small_id = null,
        ?Image          $image_middle_id = null,
        ?Image          $image_big_id = null,
        array|null      $image_cropped_options = null,
        ?bool           $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?Metatag        $metatag = null,
        ?DataCollection $directions = null,
        ?DataCollection $schools = null,
        ?DataCollection $experiences = null,
        ?DataCollection $social_medias = null,
        ?DataCollection $analyzers = null
    )
    {
        $this->id = $id;
        $this->metatag_id = $metatag_id;
        $this->name = $name;
        $this->link = $link;
        $this->text = $text;
        $this->rating = $rating;
        $this->city = $city;
        $this->comment = $comment;
        $this->additional = $additional;
        $this->copied = $copied;
        $this->image_small_id = $image_small_id;
        $this->image_middle_id = $image_middle_id;
        $this->image_big_id = $image_big_id;
        $this->image_cropped_options = $image_cropped_options;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->metatag = $metatag;
        $this->directions = $directions;
        $this->schools = $schools;
        $this->experiences = $experiences;
        $this->social_medias = $social_medias;
        $this->analyzers = $analyzers;
    }
}
