<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Entities;

use App\Models\Entities;
use App\Models\Entity;
use App\Modules\Analyzer\Entities\Analyzer;
use Carbon\Carbon;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;
use Illuminate\Http\UploadedFile;

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
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_logo_id = null;

    /**
     * Изображение сайта.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_site_id = null;

    /**
     * Метатеги.
     *
     * @var Metatag|null
     */
    public ?Metatag $metatag = null;

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
     * Анализ хранения текстов.
     *
     * @var Analyzer[]
     */
    #[Entities(Analyzer::class)]
    public ?array $analyzers = null;
}
