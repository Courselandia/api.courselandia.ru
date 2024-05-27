<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Data;

use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;
use Carbon\Carbon;

/**
 * Данные для обновления промокода.
 */
class PromocodeUpdate extends PromocodeCreate
{
    /**
     * ID промокода.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int $id ID промокода.
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
        int $id,
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
        $this->id = $id;

        parent::__construct(
            $school_id,
            $uuid,
            $code,
            $title,
            $description,
            $min_price,
            $discount,
            $discount_type,
            $date_start,
            $date_end,
            $type,
            $url,
            $status,
        );
    }
}
