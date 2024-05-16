<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */


namespace App\Modules\School\Data;

use App\Models\Data;
use Illuminate\Http\UploadedFile;

/**
 * Данные для создания школы.
 */
class SchoolCreate extends Data
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

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
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Ссылка на сайт.
     *
     * @var string|null
     */
    public ?string $site = null;

    /**
     * Реферальная ссылка.
     *
     * @var string|null
     */
    public ?string $referral = null;

    /**
     * Изображение логотипа.
     *
     * @var UploadedFile|null
     */
    public UploadedFile|null $image_logo_id = null;

    /**
     * Изображение сайта.
     *
     * @var UploadedFile|null
     */
    public UploadedFile|null $image_site_id = null;

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
        $this->name = $name;
        $this->header_template = $header_template;
        $this->link = $link;
        $this->text = $text;
        $this->additional = $additional;
        $this->rating = $rating;
        $this->site = $site;
        $this->referral = $referral;
        $this->image_logo_id = $image_logo_id;
        $this->image_site_id = $image_site_id;
        $this->status = $status;
        $this->description_template = $description_template;
        $this->keywords = $keywords;
        $this->title_template = $title_template;
    }
}
