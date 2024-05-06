<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */


namespace App\Modules\School\Data;

use Illuminate\Http\UploadedFile;

/**
 * Данные для обновления школы.
 */
class SchoolUpdate extends SchoolCreate
{
    /**
     * ID школы.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID школы.
     * @param string|null $name Название.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $link Ссылка.
     * @param string|null $text Текст.
     * @param string|null $additional Дополнительное описание.
     * @param float|null $rating Рейтинг.
     * @param string|null $site Ссылка на сайт.
     * @param string|null $referral Реферальная ссылка на сайт.
     * @param UploadedFile|null $image_logo_id Изображение логотипа.
     * @param UploadedFile|null $image_site_id Изображение сайта.
     * @param bool|null $status Статус.
     * @param string|null $description_template Шаблон описания.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title_template Шаблон заголовка.
     */
    public function __construct(
        int|string $id,
        ?string $name = null,
        ?string $header_template = null,
        ?string $link = null,
        ?string $text = null,
        ?string $additional = null,
        ?float $rating = null,
        ?string $site = null,
        ?string $referral = null,
        UploadedFile|null $image_logo_id = null,
        UploadedFile|null $image_site_id = null,
        ?bool $status = null,
        ?string $description_template = null,
        ?string $keywords = null,
        ?string $title_template = null
    ) {
        $this->id = $id;

        parent::__construct(
            $name,
            $header_template,
            $link,
            $text,
            $additional,
            $rating,
            $site,
            $referral,
            $image_logo_id,
            $image_site_id,
            $status,
            $description_template,
            $keywords,
            $title_template
        );
    }
}
