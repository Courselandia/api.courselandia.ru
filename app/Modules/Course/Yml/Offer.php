<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Yml;

/**
 * Сущность предложения.
 */
class Offer
{
    /**
     * ID курса.
     *
     * @var string|int
     */
    public string|int $id;

    /**
     * Название курса.
     *
     * @var string
     */
    public string $name;

    /**
     * Ссылка на курс.
     *
     * @var string
     */
    public string $url;

    /**
     * ID категории.
     *
     * @var int
     */
    public int|null $categoryId = null;

    /**
     * Цена по кредиту.
     *
     * @var float|null
     */
    public float|null $price_recurrent = null;

    /**
     * Цена.
     *
     * @var float
     */
    public float $price;

    /**
     * Старая цена.
     *
     * @var float|null
     */
    public float|null $price_old = null;

    /**
     * Продолжительность.
     *
     * @var int|null
     */
    public int|null $duration = null;

    /**
     * Единица измерения продолжительности.
     *
     * @var string|null
     */
    public string|null $duration_unit = null;

    /**
     * Путь к изображению.
     *
     * @var string|null
     */
    public string|null $picture = null;

    /**
     * Валюта.
     *
     * @var string
     */
    public string $currencyId;

    /**
     * Валюта.
     *
     * @var string
     */
    public string $description;
}
