<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;
use App\Modules\Course\Enums\Currency;
use App\Modules\Course\Enums\Duration;
use App\Modules\Direction\Enums\Direction;
use App\Modules\School\Enums\School;

/**
 * Сущность для разобранного курса во время импорта.
 */
class ParserCourse extends Entity
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
}
