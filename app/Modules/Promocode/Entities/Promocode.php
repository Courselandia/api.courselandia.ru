<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Entities;

use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;
use Carbon\Carbon;
use App\Models\Entity;
use App\Modules\School\Entities\School;

/**
 * Сущность для промокода.
 */
class Promocode extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID школы.
     *
     * @var int|string|null
     */
    public int|string|null $school_id = null;

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
     * Ссылка на акцию.
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
     * Признак того, что промокод действует.
     *
     * @var bool|null
     */
    public ?bool $applicable = null;

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
     * Школа.
     *
     * @var School|null
     */
    public ?School $school = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $school_id ID школы.
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
     * @param bool|string|null $status Статус.
     * @param bool|null $applicable Признак того, что промокодо действует.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param School|null $school Школа.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $school_id = null,
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
        bool|string|null $status = null,
        bool|null $applicable = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null,
        ?School $school = null,
    ) {
        $this->id = $id;
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
        $this->applicable = $applicable;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->school = $school;
    }
}
