<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entities;
use App\Models\Entity;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Metatag\Entities\Metatag;
use App\Modules\School\Entities\School;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use App\Modules\Category\Entities\Category;
use App\Modules\Direction\Entities\Direction;
use App\Modules\Image\Entities\Image;
use App\Modules\Profession\Entities\Profession;
use App\Modules\Skill\Entities\Skill;
use App\Modules\Teacher\Entities\Teacher;
use App\Modules\Tool\Entities\Tool;

/**
 * Сущность для курсов.
 */
class Course extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID источника курса.
     *
     * @var string|null
     */
    public string|null $uuid = null;

    /**
     * ID метатэгов.
     *
     * @var string|int|null
     */
    public string|int|null $metatag_id = null;

    /**
     * ID школы.
     *
     * @var string|int|null
     */
    public string|int|null $school_id = null;

    /**
     * Маленькое изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_small_id = null;

    /**
     * Среднее изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_middle_id = null;

    /**
     * Большое изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_big_id = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public string|null $header = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public string|null $text = null;

    /**
     * Заголовок морфологизированное.
     *
     * @var string|null
     */
    public string|null $header_morphy = null;

    /**
     * Описание морфологизированное.
     *
     * @var string|null
     */
    public string|null $text_morphy = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * URL на курс.
     *
     * @var string|null
     */
    public string|null $url = null;

    /**
     * Язык курса.
     *
     * @var Language|null
     */
    public Language|null $language = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public float|null $rating = null;

    /**
     * Цена.
     *
     * @var float|null
     */
    public float|null $price = null;

    /**
     * Старая цена.
     *
     * @var float|null
     */
    public float|null $price_old = null;

    /**
     * Цена по кредиту.
     *
     * @var float|null
     */
    public float|null $price_recurrent_price = null;

    /**
     * Валюта.
     *
     * @var Currency|null
     */
    public Currency|null $currency = null;

    /**
     * Онлайн статус.
     *
     * @var bool|null
     */
    public bool|null $online = null;

    /**
     * С трудоустройством.
     *
     * @var bool|null
     */
    public bool|null $employment = null;

    /**
     * Продолжительность.
     *
     * @var int|null
     */
    public int|null $duration = null;

    /**
     * Рейт продолжительность.
     *
     * @var float|null
     */
    public float|null $duration_rate = null;

    /**
     * Единица измерения продолжительности.
     *
     * @var Duration|null
     */
    public Duration|null $duration_unit = null;

    /**
     * Количество уроков.
     *
     * @var int|null
     */
    public int|null $lessons_amount = null;

    /**
     * Количество модулей.
     *
     * @var int|null
     */
    public int|null $modules_amount = null;

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
     * Статус.
     *
     * @var Status|null
     */
    public Status|null $status = null;

    /**
     * Метатеги.
     *
     * @var Metatag|null
     */
    public ?Metatag $metatag = null;

    /**
     * Школа.
     *
     * @var School|null
     */
    public ?School $school = null;

    /**
     * Направления.
     *
     * @var Direction[]
     */
    #[Entities(Direction::class)]
    public ?array $directions = null;

    /**
     * Профессии.
     *
     * @var Profession[]
     */
    #[Entities(Profession::class)]
    public ?array $professions = null;

    /**
     * Категории.
     *
     * @var Category[]
     */
    #[Entities(Category::class)]
    public ?array $categories = null;

    /**
     * Навыки.
     *
     * @var Skill[]
     */
    #[Entities(Skill::class)]
    public ?array $skills = null;

    /**
     * Учителя.
     *
     * @var Teacher[]
     */
    #[Entities(Teacher::class)]
    public ?array $teachers = null;

    /**
     * Инструменты.
     *
     * @var Tool[]
     */
    #[Entities(Tool::class)]
    public ?array $tools = null;

    /**
     * Уровни.
     *
     * @var CourseLevel[]
     */
    #[Entities(CourseLevel::class)]
    public ?array $levels = null;

    /**
     * Чему научитесь на курсе.
     *
     * @var CourseLearn[]
     */
    #[Entities(CourseLearn::class)]
    public ?array $learns = null;

    /**
     * Трудоустройство.
     *
     * @var CourseEmployment[]
     */
    #[Entities(CourseEmployment::class)]
    public ?array $employments = null;

    /**
     * Особенностей курсов.
     *
     * @var CourseFeature[]
     */
    #[Entities(CourseFeature::class)]
    public ?array $features = null;
}
