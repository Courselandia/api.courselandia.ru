<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Data;

use App\Models\Data;
use App\Modules\Promocode\Enums\DiscountType;
use Carbon\Carbon;
use App\Modules\Promocode\Enums\Type;

/**
 * Данные для создания промокода.
 */
class PromocodeCreate extends Data
{
    /**
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * ID источника промокода.
     *
     * @var string|null
     */
    public ?string $uuid = null;

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
    public ?string $title = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

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
     * @var ?Carbon
     */
    public ?Carbon $date_start = null;

    /**
     * Дата окончания.
     *
     * @var ?Carbon
     */
    public ?Carbon $date_end = null;

    /**
     * Тип промокода.
     *
     * @var ?Type
     */
    public ?Type $type = null;

    /**
     * Ссылка на сайт.
     *
     * @var ?string
     */
    public ?string $url = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * @param int|null $school_id ID школы.
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
     * @param string|null $url Ссылка на сайт.
     * @param bool|null $status Статус.
     */
    public function __construct(
        ?int $school_id = null,
        ?string $uuid = null,
        ?string $code = null,
        ?string $title = null,
        ?string $description = null,
        ?float $min_price = null,
        ?float $discount = null,
        ?DiscountType $discount_type = null,
        ?Carbon $date_start = null,
        ?Carbon $date_end = null,
        ?Type $type = null,
        ?string $url = null,
        ?bool $status = null,
    ) {
        $this->school_id = $school_id;
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
    }
}
