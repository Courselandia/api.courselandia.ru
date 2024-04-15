<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Data;

use App\Models\Data;
use Illuminate\Http\UploadedFile;

/**
 * Данные для создания учителя.
 */
class TeacherCreate extends Data
{
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
     * Скопирован.
     *
     * @var bool|null
     */
    public ?bool $copied = false;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Изображение.
     *
     * @var ?UploadedFile
     */
    public ?UploadedFile $image = null;

    /**
     * Порезанное изображение в бинарных данных.
     *
     * @var string|null
     */
    public string|null $imageCropped = null;

    /**
     * Опции порезанного изображения.
     *
     * @var array|null
     */
    public array|null $imageCroppedOptions = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $description_template = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $title_template = null;

    /**
     * ID направлений.
     *
     * @var int[]
     */
    public ?array $directions = null;

    /**
     * Опыт работы учителя.
     *
     * @var ?array<int, TeacherExperience>
     */
    public ?array $experiences = null;

    /**
     * Социальные сети учителя.
     *
     * @var ?array<int, TeacherSocialMedia>
     */
    public ?array $socialMedias = null;

    /**
     * ID школ.
     *
     * @var int[]
     */
    public ?array $schools = null;

    /**
     * @param string|null $name Название.
     * @param string|null $link Ссылка.
     * @param string|null $text Текст.
     * @param string|null $city Город.
     * @param string|null $comment Комментарий.
     * @param string|null $additional Дополнительное описание.
     * @param bool|null $copied Скопирован.
     * @param float|null $rating Рейтинг.
     * @param UploadedFile|null $image Изображение.
     * @param string|null $imageCropped Порезанное изображение в бинарных данных.
     * @param array|null $imageCroppedOptions Опции порезанного изображения.
     * @param bool|null $status Статус.
     * @param string|null $description_template Шаблон описания.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title_template Шаблон заголовка.
     * @param array|null $directions ID направлений.
     * @param ?array<int, TeacherExperience> $experiences Опыт работы учителя.
     * @param ?array<int, TeacherSocialMedia> $socialMedias Социальные сети учителя.
     * @param array|null $schools ID школ.
     */
    public function __construct(
        ?string       $name = null,
        ?string       $link = null,
        ?string       $text = null,
        ?string       $city = null,
        ?string       $comment = null,
        ?string       $additional = null,
        ?bool         $copied = false,
        ?float        $rating = null,
        ?UploadedFile $image = null,
        string|null   $imageCropped = null,
        array|null    $imageCroppedOptions = null,
        ?bool         $status = null,
        string        $description_template = null,
        ?string       $keywords = null,
        ?string       $title_template = null,
        ?array        $directions = null,
        ?array        $experiences = null,
        ?array        $socialMedias = null,
        ?array        $schools = null
    )
    {
        $this->name = $name;
        $this->link = $link;
        $this->text = $text;
        $this->city = $city;
        $this->comment = $comment;
        $this->additional = $additional;
        $this->copied = $copied;
        $this->rating = $rating;
        $this->image = $image;
        $this->imageCropped = $imageCropped;
        $this->imageCroppedOptions = $imageCroppedOptions;
        $this->status = $status;
        $this->description_template = $description_template;
        $this->keywords = $keywords;
        $this->title_template = $title_template;
        $this->directions = $directions;
        $this->experiences = $experiences;
        $this->socialMedias = $socialMedias;
        $this->schools = $schools;
    }
}
