<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Data\Actions;

use App\Models\Data;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use App\Modules\Salary\Enums\Level;
use Illuminate\Http\UploadedFile;

/**
 * Данные для действия создание курса.
 */
class CourseCreate extends Data
{
    /**
     * ID школы.
     *
     * @var string|int|null
     */
    public string|int|null $school_id = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public UploadedFile|null $image = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public string|null $name = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public string|null $header_template = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public string|null $text = null;

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
    public float|null $price_recurrent = null;

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
     * Программа курса.
     *
     * @var array|null
     */
    public array|null $program = null;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public Status|null $status = null;

    /**
     * Шаблон описание.
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
     * ID профессий.
     *
     * @var int[]
     */
    public ?array $professions = null;

    /**
     * ID категорий.
     *
     * @var int[]
     */
    public ?array $categories = null;

    /**
     * ID навыков.
     *
     * @var int[]
     */
    public ?array $skills = null;

    /**
     * ID учителей.
     *
     * @var int[]
     */
    public ?array $teachers = null;

    /**
     * ID инструментов.
     *
     * @var int[]
     */
    public ?array $tools = null;

    /**
     * ID как проходит обучение.
     *
     * @var int[]
     */
    public ?array $processes = null;

    /**
     * Уровни.
     *
     * @var Level[]
     */
    public ?array $levels = null;

    /**
     * Что будет изучено.
     *
     * @var string[]
     */
    public ?array $learns = null;

    /**
     * Помощь в трудоустройстве.
     *
     * @var int[]
     */
    public ?array $employments = null;

    /**
     * Особенности курса.
     *
     * @var array|null
     */
    public ?array $features = null;

    /**
     * @param string|int|null $school_id ID школы.
     * @param UploadedFile|null $image Изображение.
     * @param string|null $name Название.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $text Описание.
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
     * @param int|null $duration Продолжительность
     * @param Duration|null $duration_unit Единица измерения продолжительности.
     * @param int|null $lessons_amount Количество уроков.
     * @param int|null $modules_amount Количество модулей.
     * @param array|null $program Программа курса.
     * @param Status|null $status Статус.
     * @param string|null $description_template Шаблон описание.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title_template Шаблон заголовка.
     * @param array|null $directions ID направлений.
     * @param array|null $professions ID профессий.
     * @param array|null $categories ID категорий.
     * @param array|null $skills ID навыков.
     * @param array|null $teachers ID учителей.
     * @param array|null $tools ID инструментов.
     * @param array|null $processes ID как проходит обучение.
     * @param array|null $levels Уровни.
     * @param array|null $learns Что будет изучено.
     * @param array|null $employments Помощь в трудоустройстве.
     * @param array|null $features Особенности курса.
     */
    public function __construct(
        string|int|null   $school_id = null,
        UploadedFile|null $image = null,
        string|null       $name = null,
        string|null       $header_template = null,
        string|null       $text = null,
        string|null       $link = null,
        string|null       $url = null,
        Language|null     $language = null,
        float|null        $rating = null,
        float|null        $price = null,
        float|null        $price_old = null,
        float|null        $price_recurrent = null,
        Currency|null     $currency = null,
        bool|null         $online = null,
        bool|null         $employment = null,
        int|null          $duration = null,
        Duration|null     $duration_unit = null,
        int|null          $lessons_amount = null,
        int|null          $modules_amount = null,
        array|null        $program = null,
        Status|null       $status = null,
        ?string           $description_template = null,
        ?string           $keywords = null,
        ?string           $title_template = null,
        ?array            $directions = null,
        ?array            $professions = null,
        ?array            $categories = null,
        ?array            $skills = null,
        ?array            $teachers = null,
        ?array            $tools = null,
        ?array            $processes = null,
        ?array            $levels = null,
        ?array            $learns = null,
        ?array            $employments = null,
        ?array            $features = null,
    )
    {
        $this->school_id = $school_id;
        $this->image = $image;
        $this->name = $name;
        $this->header_template = $header_template;
        $this->text = $text;
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
        $this->duration_unit = $duration_unit;
        $this->lessons_amount = $lessons_amount;
        $this->modules_amount = $modules_amount;
        $this->program = $program;
        $this->status = $status;
        $this->description_template = $description_template;
        $this->keywords = $keywords;
        $this->title_template = $title_template;
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
    }
}
