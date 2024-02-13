<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;
use App\Modules\Analyzer\Entities\Analyzer;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Employment\Entities\Employment;
use App\Modules\Metatag\Entities\Metatag;
use App\Modules\School\Entities\School;
use Carbon\Carbon;
use App\Modules\Category\Entities\Category;
use App\Modules\Direction\Entities\Direction;
use App\Modules\Image\Entities\Image;
use App\Modules\Profession\Entities\Profession;
use App\Modules\Skill\Entities\Skill;
use App\Modules\Teacher\Entities\Teacher;
use App\Modules\Tool\Entities\Tool;
use App\Modules\Process\Entities\Process;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

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
     * @var ?string
     */
    public ?string $uuid = null;

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
     * @var ?Image
     */
    public ?Image $image_small_id = null;

    /**
     * Среднее изображение.
     *
     * @var ?Image
     */
    public ?Image $image_middle_id = null;

    /**
     * Большое изображение.
     *
     * @var ?Image
     */
    public ?Image $image_big_id = null;

    /**
     * Название.
     *
     * @var ?string
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var ?string
     */
    public ?string $header = null;

    /**
     * Шаблон заголовка.
     *
     * @var ?string
     */
    public ?string $header_template = null;

    /**
     * Описание.
     *
     * @var ?string
     */
    public ?string $text = null;

    /**
     * Заголовок морфологизированное.
     *
     * @var ?string
     */
    public ?string $name_morphy = null;

    /**
     * Описание морфологизированное.
     *
     * @var ?string
     */
    public ?string $text_morphy = null;

    /**
     * Ссылка.
     *
     * @var ?string
     */
    public ?string $link = null;

    /**
     * URL на курс.
     *
     * @var ?string
     */
    public ?string $url = null;

    /**
     * Язык курса.
     *
     * @var ?Language
     */
    public ?Language $language = null;

    /**
     * Рейтинг.
     *
     * @var ?float
     */
    public ?float $rating = null;

    /**
     * Цена.
     *
     * @var ?float
     */
    public ?float $price = null;

    /**
     * Старая цена.
     *
     * @var ?float
     */
    public ?float $price_old = null;

    /**
     * Цена по кредиту.
     *
     * @var ?float
     */
    public ?float $price_recurrent = null;

    /**
     * Валюта.
     *
     * @var ?Currency
     */
    public ?Currency $currency = null;

    /**
     * Онлайн статус.
     *
     * @var ?bool
     */
    public ?bool $online = null;

    /**
     * С трудоустройством.
     *
     * @var ?bool
     */
    public ?bool $employment = null;

    /**
     * Продолжительность.
     *
     * @var ?int
     */
    public ?int $duration = null;

    /**
     * Рейт продолжительность.
     *
     * @var ?float
     */
    public ?float $duration_rate = null;

    /**
     * Единица измерения продолжительности.
     *
     * @var ?Duration
     */
    public ?Duration $duration_unit = null;

    /**
     * Количество уроков.
     *
     * @var ?int
     */
    public ?int $lessons_amount = null;

    /**
     * Количество модулей.
     *
     * @var ?int
     */
    public ?int $modules_amount = null;

    /**
     * Программа курса.
     *
     * @var ?array
     */
    public ?array $program = null;

    /**
     * Активные направления.
     *
     * @var ?array
     */
    public ?array $direction_ids = null;

    /**
     * Активные профессии.
     *
     * @var ?array
     */
    public ?array $profession_ids = null;

    /**
     * Активные категории.
     *
     * @var ?array
     */
    public ?array $category_ids = null;

    /**
     * Активные навыки.
     *
     * @var ?array
     */
    public ?array $skill_ids = null;

    /**
     * Активные учителя.
     *
     * @var ?array
     */
    public ?array $teacher_ids = null;

    /**
     * Активные инструменты.
     *
     * @var ?array
     */
    public ?array $tool_ids = null;

    /**
     * Активные уровни.
     *
     * @var ?array
     */
    public ?array $level_values = null;

    /**
     * Признак если активная школа.
     *
     * @var ?bool
     */
    public ?bool $has_active_school = null;

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
     * @var ?Status
     */
    public ?Status $status = null;

    /**
     * Метатеги.
     *
     * @var ?Metatag
     */
    public ?Metatag $metatag = null;

    /**
     * Школа.
     *
     * @var ?School
     */
    public ?School $school = null;

    /**
     * Направления.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Direction::class)]
    public ?DataCollection $directions = null;

    /**
     * Профессии.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Profession::class)]
    public ?DataCollection $professions = null;

    /**
     * Категории.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Category::class)]
    public ?DataCollection $categories = null;

    /**
     * Навыки.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Skill::class)]
    public ?DataCollection $skills = null;

    /**
     * Учителя.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Teacher::class)]
    public ?DataCollection $teachers = null;

    /**
     * Инструменты.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Tool::class)]
    public ?DataCollection $tools = null;

    /**
     * Как проходит обучение.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Process::class)]
    public ?DataCollection $processes = null;

    /**
     * Уровни.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseLevel::class)]
    public ?DataCollection $levels = null;

    /**
     * Чему научитесь на курсе.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseLearn::class)]
    public ?DataCollection $learns = null;

    /**
     * Трудоустройство.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Employment::class)]
    public ?DataCollection $employments = null;

    /**
     * Особенностей курсов.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseFeature::class)]
    public ?DataCollection $features = null;

    /**
     * Анализ хранения текстов.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(Analyzer::class)]
    public ?DataCollection $analyzers = null;
}
