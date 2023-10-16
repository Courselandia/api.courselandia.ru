<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Yml;

/**
 * Сущность валюты.
 */
class Currency
{
    /**
     * Валюта.
     *
     * @var string
     */
    public string $id;

    /**
     * Рейт.
     *
     * @var int
     */
    public int $rate;
}
