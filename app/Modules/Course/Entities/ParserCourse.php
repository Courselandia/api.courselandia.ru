<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\EntityNew;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Direction\Enums\Direction;
use App\Modules\School\Enums\School;

/**
 * Сущность для разобранного курса во время импорта.
 */
class ParserCourse extends EntityNew
{
    /**
     * ID источника курса.
     *
     * @var string|null
     */
    public string|null $uuid = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public string|null $name = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public string|null $text = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public bool|null $status = null;

    /**
     * URL.
     *
     * @var string|null
     */
    public string|null $url = null;

    /**
     * Изображение.
     *
     * @var string|null
     */
    public string|null $image = null;

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
     * Количество уроков.
     *
     * @var int|null
     */
    public int|null $lessons_amount = null;

    /**
     * Валюта.
     *
     * @var Currency|null
     */
    public Currency|null $currency = null;

    /**
     * Школа.
     *
     * @var School|null
     */
    public School|null $school = null;

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
     * Направление.
     *
     * @var Direction|null
     */
    public Direction|null $direction = null;

    /**
     * С трудоустройством.
     *
     * @var bool|null
     */
    public bool|null $employment = null;

    /**
     * @param string|null $uuid ID источника курса.
     * @param string|null $name Название.
     * @param string|null $text Описание.
     * @param bool|null $status Статус.
     * @param string|null $url URL.
     * @param string|null $image Изображение.
     * @param float|null $price Цена.
     * @param float|null $price_old Старая цена.
     * @param float|null $price_recurrent Цена по кредиту.
     * @param int|null $lessons_amount Количество уроков.
     * @param Currency|null $currency Валюта.
     * @param School|null $school Школа.
     * @param int|null $duration Продолжительность.
     * @param Duration|null $duration_unit Единица измерения продолжительности.
     * @param Direction|null $direction Направление.
     * @param bool|null $employment С трудоустройством.
     */
    public function __construct(
        string|null    $uuid = null,
        string|null    $name = null,
        string|null    $text = null,
        bool|null      $status = null,
        string|null    $url = null,
        string|null    $image = null,
        float|null     $price = null,
        float|null     $price_old = null,
        float|null     $price_recurrent = null,
        int|null       $lessons_amount = null,
        Currency|null  $currency = null,
        School|null    $school = null,
        int|null       $duration = null,
        Duration|null  $duration_unit = null,
        Direction|null $direction = null,
        bool|null      $employment = null,
    )
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->text = $text;
        $this->status = $status;
        $this->url = $url;
        $this->image = $image;
        $this->price = $price;
        $this->price_old = $price_old;
        $this->price_recurrent = $price_recurrent;
        $this->lessons_amount = $lessons_amount;
        $this->currency = $currency;
        $this->school = $school;
        $this->duration = $duration;
        $this->duration_unit = $duration_unit;
        $this->direction = $direction;
        $this->employment = $employment;
    }
}
