<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Entities;

use App\Models\Entity;
use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;
use App\Modules\School\Enums\School;
use Carbon\Carbon;

/**
 * Сущность для разобранной промокода во время импорта.
 */
class ParserPromocode extends Entity
{
    /**
     * ID источника промокода.
     *
     * @var string|null
     */
    public string|null $uuid = null;

    /**
     * Промокод.
     *
     * @var string|null
     */
    public ?string $code = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public string|null $title = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public string|null $description = null;

    /**
     * Минмальная цена.
     *
     * @var ?float
     */
    public ?float $min_price = null;

    /**
     * Скидка.
     *
     * @var ?float
     */
    public ?float $discount = null;

    /**
     * Тип скидки.
     *
     * @var ?DiscountType
     */
    public ?DiscountType $discount_type = null;

    /**
     * Дата начала.
     *
     * @var Carbon|null
     */
    public Carbon|null $date_start = null;

    /**
     * Дата окончания.
     *
     * @var Carbon|null
     */
    public Carbon|null $date_end = null;

    /**
     * Тип промокода.
     *
     * @var ?Type
     */
    public ?Type $type = null;

    /**
     * Ссылка на акцию.
     *
     * @var string|null
     */
    public string|null $url = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public bool|null $status = null;

    /**
     * Школа.
     *
     * @var School|null
     */
    public School|null $school = null;

    /**
     * @param string|null $uuid ID источника промокода.
     * @param string|null $code Промокод.
     * @param string|null $title Название.
     * @param string|null $description Описание.
     * @param float|null $min_price Минмальная цена.
     * @param float|null $discount Скидка.
     * @param DiscountType|null $discount_type Тип скидки.
     * @param Carbon|null $date_start Дата начала.
     * @param Carbon|null $date_end Дата окончания.
     * @param Type|null $type Тип промокода.
     * @param string|null $url Ссылка на акцию.
     * @param bool|null $status Статус.
     * @param School|null $school Школа.
     */
    public function __construct(
        string|null $uuid = null,
        ?string $code = null,
        string|null $title = null,
        string|null $description = null,
        ?float $min_price = null,
        ?float $discount = null,
        ?DiscountType $discount_type = null,
        Carbon|null $date_start = null,
        Carbon|null $date_end = null,
        ?Type $type = null,
        string|null $url = null,
        bool|null $status = null,
        School|null $school = null,
    ) {
        $this->uuid = $uuid;
        $this->code = $code;
        $this->title = $title;
        $this->description = $description;
        $this->min_price = $min_price;
        $this->discount = $discount;
        $this->discount_type = $discount_type;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->type = $type;
        $this->url = $url;
        $this->status = $status;
        $this->school = $school;
    }
}
