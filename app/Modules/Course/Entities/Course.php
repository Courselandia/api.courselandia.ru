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
     * @var ?array<int, Direction>
     */
    public ?array $directions = null;

    /**
     * Профессии.
     *
     * @var ?array<int, Profession>
     */
    public ?array $professions = null;

    /**
     * Категории.
     *
     * @var ?array<int, Category>
     */
    public ?array $categories = null;

    /**
     * Навыки.
     *
     * @var ?array<int, Skill>
     */
    public ?array $skills = null;

    /**
     * Учителя.
     *
     * @var ?array<int, Teacher>
     */
    public ?array $teachers = null;

    /**
     * Инструменты.
     *
     * @var ?array<int, Tool>
     */
    public ?array $tools = null;

    /**
     * Как проходит обучение.
     *
     * @var ?array<int, Process>
     */
    public ?array $processes = null;

    /**
     * Уровни.
     *
     * @var ?array<int, CourseLevel>
     */
    public ?array $levels = null;

    /**
     * Чему научитесь на курсе.
     *
     * @var ?array<int, CourseLearn>
     */
    public ?array $learns = null;

    /**
     * Трудоустройство.
     *
     * @var ?array<int, Employment>
     */
    public ?array $employments = null;

    /**
     * Особенностей курсов.
     *
     * @var ?array<int, CourseFeature>
     */
    public ?array $features = null;

    /**
     * Анализ хранения текстов.
     *
     * @var ?array<int, Analyzer>
     */
    public ?array $analyzers = null;

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $uuid ID источника курса.
     * @param string|int|null $metatag_id ID метатэгов.
     * @param string|null $school_id ID школы.
     * @param Image|null $image_small_id Маленькое изображение.
     * @param Image|null $image_middle_id Среднее изображение.
     * @param Image|null $image_big_id Большое изображение.
     * @param string|null $name Название.
     * @param string|null $header Заголовок.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $text Описание.
     * @param string|null $name_morphy Заголовок морфологизированное.
     * @param string|null $text_morphy Описание морфологизированное.
     * @param string|null $link Ссылка.
     * @param string|null $url URL на курс.
     * @param Language|null $language Язык курса.
     * @param float|null $rating Рейтинг.
     * @param float|null $price Цена.
     * @param float|null $price_old Старая цена.
     * @param float|null $price_recurrent Цена по кредиту.
     * @param Currency|null $currency Валюта.
     * @param bool|null $online Онлайн статус.
     * @param bool|null $employment С трудоустройством.
     * @param int|null $duration Продолжительность.
     * @param float|null $duration_rate Рейт продолжительность.
     * @param Duration|null $duration_unit Единица измерения продолжительности.
     * @param int|null $lessons_amount Количество уроков.
     * @param int|null $modules_amount Количество модулей.
     * @param array|null $program Программа курса.
     * @param array|null $direction_ids Активные направления.
     * @param array|null $profession_ids Активные профессии.
     * @param array|null $category_ids Активные категории.
     * @param array|null $skill_ids Активные навыки.
     * @param array|null $teacher_ids Активные учителя.
     * @param array|null $tool_ids Активные инструменты.
     * @param array|null $level_values Активные уровни.
     * @param bool|null $has_active_school Признак если активная школа.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param Status|null $status Статус.
     * @param Metatag|null $metatag Метатеги.
     * @param School|null $school Школа.
     * @param array|null $directions Направления.
     * @param array|null $professions Профессии.
     * @param array|null $categories Категории.
     * @param array|null $skills Навыки.
     * @param array|null $teachers Учителя.
     * @param array|null $tools Инструменты.
     * @param array|null $processes Как проходит обучение.
     * @param array|null $levels Уровни.
     * @param array|null $learns Чему научитесь на курсе.
     * @param array|null $employments Трудоустройство.
     * @param array|null $features Особенностей курсов.
     * @param array|null $analyzers Анализ хранения текстов.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $uuid = null,
        string|int|null $metatag_id = null,
        ?string         $school_id = null,
        ?Image          $image_small_id = null,
        ?Image          $image_middle_id = null,
        ?Image          $image_big_id = null,
        ?string         $name = null,
        ?string         $header = null,
        ?string         $header_template = null,
        ?string         $text = null,
        ?string         $name_morphy = null,
        ?string         $text_morphy = null,
        ?string         $link = null,
        ?string         $url = null,
        ?Language       $language = null,
        ?float          $rating = null,
        ?float          $price = null,
        ?float          $price_old = null,
        ?float          $price_recurrent = null,
        ?Currency       $currency = null,
        ?bool           $online = null,
        ?bool           $employment = null,
        ?int            $duration = null,
        ?float          $duration_rate = null,
        ?Duration       $duration_unit = null,
        ?int            $lessons_amount = null,
        ?int            $modules_amount = null,
        ?array          $program = null,
        ?array          $direction_ids = null,
        ?array          $profession_ids = null,
        ?array          $category_ids = null,
        ?array          $skill_ids = null,
        ?array          $teacher_ids = null,
        ?array          $tool_ids = null,
        ?array          $level_values = null,
        ?bool           $has_active_school = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?Status         $status = null,
        ?Metatag        $metatag = null,
        ?School         $school = null,
        ?array          $directions = null,
        ?array          $professions = null,
        ?array          $categories = null,
        ?array          $skills = null,
        ?array          $teachers = null,
        ?array          $tools = null,
        ?array          $processes = null,
        ?array          $levels = null,
        ?array          $learns = null,
        ?array          $employments = null,
        ?array          $features = null,
        ?array          $analyzers = null,
    )
    {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->metatag_id = $metatag_id;
        $this->school_id = $school_id;
        $this->image_small_id = $image_small_id;
        $this->image_middle_id = $image_middle_id;
        $this->image_big_id = $image_big_id;
        $this->name = $name;
        $this->header = $header;
        $this->header_template = $header_template;
        $this->text = $text;
        $this->name_morphy = $name_morphy;
        $this->text_morphy = $text_morphy;
        $this->link = $link;
        $this->url = $url;
        $this->language = $language;
        $this->rating = $rating;
        $this->price = $price;
        $this->price_old = $price_old;
        $this->price_recurrent = $price_recurrent;
        $this->currency = $currency;
        $this->online = $online;
        $this->employment = $employment;
        $this->duration = $duration;
        $this->duration_rate = $duration_rate;
        $this->duration_unit = $duration_unit;
        $this->lessons_amount = $lessons_amount;
        $this->modules_amount = $modules_amount;
        $this->program = $program;
        $this->direction_ids = $direction_ids;
        $this->profession_ids = $profession_ids;
        $this->category_ids = $category_ids;
        $this->skill_ids = $skill_ids;
        $this->teacher_ids = $teacher_ids;
        $this->tool_ids = $tool_ids;
        $this->level_values = $level_values;
        $this->has_active_school = $has_active_school;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->status = $status;
        $this->metatag = $metatag;
        $this->school = $school;
        $this->directions = $directions;
        $this->professions = $professions;
        $this->categories = $categories;
        $this->skills = $skills;
        $this->teachers = $teachers;
        $this->tools = $tools;
        $this->processes = $processes;
        $this->levels = $levels;
        $this->learns = $learns;
        $this->employments = $employments;
        $this->features = $features;
        $this->analyzers = $analyzers;
    }
}
