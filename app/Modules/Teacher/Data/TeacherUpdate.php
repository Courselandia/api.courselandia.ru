<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Data;

use Illuminate\Http\UploadedFile;

/**
 * Данные для обновления учителя.
 */
class TeacherUpdate extends TeacherCreate
{
    /**
     * ID учителя.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID учителя.
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
        int|string    $id,
        ?string       $name = null,
        ?string       $link = null,
        ?string       $text = null,
        ?string       $city = null,
        ?string       $comment = null,
        ?string       $additional = null,
        ?bool         $copied = null,
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
        $this->id = $id;

        parent::__construct(
            $name,
            $link,
            $text,
            $city,
            $comment,
            $additional,
            $copied,
            $rating,
            $image,
            $imageCropped,
            $imageCroppedOptions,
            $status,
            $description_template,
            $keywords,
            $title_template,
            $directions,
            $experiences,
            $socialMedias,
            $schools,
        );
    }
}
