<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Entities;

use App\Models\Entities;
use App\Models\EntityNew;
use App\Modules\Analyzer\Entities\Analyzer;
use Carbon\Carbon;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;
use Illuminate\Http\UploadedFile;

/**
 * Сущность для школ.
 */
class School extends EntityNew
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
     * Сайт.
     *
     * @var string|null
     */
    public ?string $site = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Изображение логотипа.
     *
     * @var ?Image
     */
    public ?Image $image_logo_id = null;

    /**
     * Изображение сайта.
     *
     * @var ?Image
     */
    public ?Image $image_site_id = null;

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
     * Количество отзывов.
     *
     * @var ?int
     */
    public ?int $reviews_count = null;

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
    #[Entities(Analyzer::class)]
    public ?array $analyzers = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $metatag_id ID метатегов.
     * @param string|null $name Название.
     * @param string|null $header Заголовок.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $link Ссылка.
     * @param string|null $text Статья.
     * @param string|null $site Сайт.
     * @param float|null $rating Рейтинг.
     * @param Image|null $image_logo_id Изображение логотипа.
     * @param Image|null $image_site_id Изображение сайта.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param int|null $reviews_count Количество отзывов.
     * @param int|null $reviews_1_star_count Количество отзывов с одной звездой.
     * @param int|null $reviews_2_stars_count Количество отзывов с двумя звездами.
     * @param int|null $reviews_3_stars_count Количество отзывов с тремя звездами.
     * @param int|null $reviews_4_stars_count Количество отзывов с четырмя звездами.
     * @param int|null $reviews_5_stars_count Количество отзывов с пятью звездами.
     * @param array|null $amount_courses Количество курсов.
     * @param Metatag|null $metatag Метатеги.
     * @param array|null $analyzers Анализ хранения текстов.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $metatag_id = null,
        ?string $name = null,
        ?string $header = null,
        ?string $header_template = null,
        ?string $link = null,
        ?string $text = null,
        ?string $site = null,
        ?float $rating = null,
        ?Image $image_logo_id = null,
        ?Image $image_site_id = null,
        ?bool $status = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null,
        ?int $reviews_count = null,
        ?int $reviews_1_star_count = null,
        ?int $reviews_2_stars_count = null,
        ?int $reviews_3_stars_count = null,
        ?int $reviews_4_stars_count = null,
        ?int $reviews_5_stars_count = null,
        array|null $amount_courses = null,
        ?Metatag $metatag = null,
        ?array $analyzers = null
    )
    {

    }
}
