<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Data\Actions;

use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Language;
use App\Modules\Course\Enums\Status;
use Illuminate\Http\UploadedFile;

/**
 * Данные для действия обновления курса.
 */
class CourseUpdate extends CourseCreate
{
    /**
     * ID курса.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID курса.
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
        int|string        $id,
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
        $this->id = $id;

        parent::__construct(
            $school_id,
            $image,
            $name,
            $header_template,
            $text,
            $link,
            $url,
            $language,
            $rating,
            $price,
            $price_old,
            $price_recurrent,
            $currency,
            $online,
            $employment,
            $duration,
            $duration_unit,
            $lessons_amount,
            $modules_amount,
            $program,
            $status,
            $description_template,
            $keywords,
            $title_template,
            $directions,
            $professions,
            $categories,
            $skills,
            $teachers,
            $tools,
            $processes,
            $levels,
            $learns,
            $employments,
            $features,
        );
    }
}
