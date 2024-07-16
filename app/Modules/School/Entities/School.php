<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Entities;

use App\Models\Entity;
use App\Modules\Analyzer\Entities\Analyzer;
use App\Modules\Promocode\Entities\Promocode;
use App\Modules\Promotion\Entities\Promotion;
use Carbon\Carbon;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;

/**
 * Сущность для школ.
 */
class School extends Entity
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
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

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
     * Сайт.
     *
     * @var string|null
     */
    public ?string $site = null;

    /**
     * Реферальная ссылка на сайт..
     *
     * @var string|null
     */
    public ?string $referral = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Изображение логотипа.
     *
     * @var Image|null
     */
    public Image|null $image_logo_id = null;

    /**
     * Изображение сайта.
     *
     * @var Image|null
     */
    public Image|null $image_site_id = null;

    /**
     * Изображение логотипа.
     *
     * @var Image|null
     */
    public Image|null $image_logo = null;

    /**
     * Изображение сайта.
     *
     * @var Image|null
     */
    public Image|null $image_site = null;

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
     * Количество отзывов с одной звездой.
     *
     * @var ?int
     */
    public ?int $reviews_1_star_count = null;

    /**
     * Количество отзывов с двумя звездами.
     *
     * @var ?int
     */
    public ?int $reviews_2_stars_count = null;

    /**
     * Количество отзывов с тремя звездами.
     *
     * @var ?int
     */
    public ?int $reviews_3_stars_count = null;

    /**
     * Количество отзывов с четырмя звездами.
     *
     * @var ?int
     */
    public ?int $reviews_4_stars_count = null;

    /**
     * Количество отзывов с пятью звездами.
     *
     * @var ?int
     */
    public ?int $reviews_5_stars_count = null;

    /**
     * Количество курсов.
     *
     * @var array|null
     */
    public array|null $amount_courses = null;

    /**
     * Количество учителей.
     *
     * @var int|null
     */
    public int|null $amount_teachers = null;

    /**
     * Количество отзывов.
     *
     * @var int|null
     */
    public int|null $amount_reviews = null;

    /**
     * Метатеги.
     *
     * @var Metatag|null
     */
    public ?Metatag $metatag = null;

    /**
     * Анализ хранения текстов.
     *
     * @var Analyzer[]
     */
    public ?array $analyzers = null;

    /**
     * Промокоды.
     *
     * @var Promocode[]
     */
    public ?array $promocodes = null;

    /**
     * Самый выгодный промокод.
     *
     * @var ?Promocode
     */
    public ?Promocode $promocode = null;

    /**
     * Промоакции.
     *
     * @var Promotion[]
     */
    public ?array $promotions = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $metatag_id ID метатегов.
     * @param string|null $name Название.
     * @param string|null $header Заголовок.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $link Ссылка.
     * @param string|null $text Статья.
     * @param string|null $additional Дополнительное описание.
     * @param string|null $site Сайт.
     * @param string|null $referral Реферальная ссылка на сайт.
     * @param float|null $rating Рейтинг.
     * @param Image|null $image_logo_id Изображение логотипа.
     * @param Image|null $image_site_id Изображение сайта.
     * @param Image|null $image_logo Изображение логотипа.
     * @param Image|null $image_site Изображение сайта.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param int|null $reviews_1_star_count Количество отзывов с одной звездой.
     * @param int|null $reviews_2_stars_count Количество отзывов с двумя звездами.
     * @param int|null $reviews_3_stars_count Количество отзывов с тремя звездами.
     * @param int|null $reviews_4_stars_count Количество отзывов с четырмя звездами.
     * @param int|null $reviews_5_stars_count Количество отзывов с пятью звездами.
     * @param array|null $amount_courses Количество курсов.
     * @param int|null $amount_teachers Количество учителей.
     * @param int|null $amount_reviews Количество отзывов.
     * @param Metatag|null $metatag Метатеги.
     * @param array|null $analyzers Анализ хранения текстов.
     * @param array|null $promocodes Промокоды.
     * @param Promocode|null $promocode Самый выгодный промокод.
     * @param array|null $promotions Промоакции.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $metatag_id = null,
        ?string $name = null,
        ?string $header = null,
        ?string $header_template = null,
        ?string $link = null,
        ?string $text = null,
        ?string $additional = null,
        ?string $site = null,
        ?string $referral = null,
        ?float $rating = null,
        ?Image $image_logo_id = null,
        ?Image $image_site_id = null,
        ?Image $image_logo = null,
        ?Image $image_site = null,
        ?bool $status = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null,
        ?int $reviews_1_star_count = null,
        ?int $reviews_2_stars_count = null,
        ?int $reviews_3_stars_count = null,
        ?int $reviews_4_stars_count = null,
        ?int $reviews_5_stars_count = null,
        array|null $amount_courses = null,
        int|null $amount_teachers = null,
        int|null $amount_reviews = null,
        ?Metatag $metatag = null,
        ?array $analyzers = null,
        array $promocodes = null,
        ?Promocode $promocode = null,
        array $promotions = null,
    ) {
        $this->id = $id;
        $this->metatag_id = $metatag_id;
        $this->name = $name;
        $this->header = $header;
        $this->header_template = $header_template;
        $this->link = $link;
        $this->text = $text;
        $this->additional = $additional;
        $this->site = $site;
        $this->referral = $referral;
        $this->rating = $rating;
        $this->image_logo_id = $image_logo_id;
        $this->image_site_id = $image_site_id;
        $this->image_logo = $image_logo;
        $this->image_site = $image_site;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->reviews_1_star_count = $reviews_1_star_count;
        $this->reviews_2_stars_count = $reviews_2_stars_count;
        $this->reviews_3_stars_count = $reviews_3_stars_count;
        $this->reviews_4_stars_count = $reviews_4_stars_count;
        $this->reviews_5_stars_count = $reviews_5_stars_count;
        $this->amount_courses = $amount_courses;
        $this->amount_teachers = $amount_teachers;
        $this->amount_reviews = $amount_reviews;
        $this->metatag = $metatag;
        $this->analyzers = $analyzers;
        $this->promocodes = $promocodes;
        $this->promocode = $promocode;
        $this->promotions = $promotions;
    }
}
