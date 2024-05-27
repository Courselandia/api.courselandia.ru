<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Filters;

use Config;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;
use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;

/**
 * Класс фильтр для таблицы промокодов.
 */
class PromocodeFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('promocodes.id', $id);
    }

    /**
     * Поиск по школам.
     *
     * @param array|int|string $schoolIds ID's школ.
     *
     * @return self Правила поиска.
     */
    public function schoolId(array|int|string $schoolIds): self
    {
        return $this->whereIn('promocodes.school_id', is_array($schoolIds) ? $schoolIds : [$schoolIds]);
    }

    /**
     * Поиск по дате статьи.
     *
     * @param array $dates Даты от и до.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function date(array $dates): self
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->where('promocodes.date_start', '>=', $dates[0])
            ->where('promocodes.date_start', '<=', $dates[1]);
    }

    /**
     * Поиск по коду.
     *
     * @param string $query Строка поиска.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function code(string $query): self
    {
        return $this->whereLike('promocodes.code', $query);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function title(string $query): self
    {
        return $this->whereLike('promocodes.title', $query);
    }

    /**
     * Поиск по оптсанию.
     *
     * @param string $query Строка поиска.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function description(string $query): self
    {
        return $this->whereLike('promocodes.description', $query);
    }

    /**
     * Поиск по минимальной цене.
     *
     * @param float[]|float $price Цена от и до.
     *
     * @return self Правила поиска.
     */
    public function minPrice(array|float $price): self
    {
        if (is_array($price)) {
            return $this->whereBetween('promocodes.min_price', $price);
        }

        return $this->where('promocodes.min_price', $price);
    }

    /**
     * Поиск по скидке.
     *
     * @param float[]|float $discount Скидка от и до.
     *
     * @return self Правила поиска.
     */
    public function discount(array|float $discount): self
    {
        if (is_array($discount)) {
            return $this->whereBetween('promocodes.discount', $discount);
        }

        return $this->where('promocodes.discount', $discount);
    }

    /**
     * Поиск по типу скидок.
     *
     * @param DiscountType[]|DiscountType|string $types Типы скидок.
     *
     * @return self Правила поиска.
     */
    public function discountType(array|DiscountType|string $types): self
    {
        return $this->whereIn('promocodes.discount_type', is_array($types) ? $types : [$types]);
    }

    /**
     * Поиск по началу действия промокода.
     *
     * @param string $date Дата.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function dateStart(string $date): self
    {
        return $this->where('promocodes.date_start', '>=',
            Carbon::createFromFormat('Y-m-d O', $date)->startOfDay()->setTimezone(Config::get('app.timezone')));
    }

    /**
     * Поиск по окончания действия промокода.
     *
     * @param string $date Дата.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function dateEnd(string $date): self
    {
        return $this->where('promocodes.date_end', '>=',
            Carbon::createFromFormat('Y-m-d O', $date)->endOfDay()->setTimezone(Config::get('app.timezone')));
    }

    /**
     * Поиск по типу промокодов.
     *
     * @param Type[]|Type|string $types Типы промокодов.
     *
     * @return self Правила поиска.
     */
    public function type(array|Type|string $types): self
    {
        return $this->whereIn('promocodes.type', is_array($types) ? $types : [$types]);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('promocodes.status', $status);
    }

    /**
     * Поиск по возможности применить.
     *
     * @param bool $status Статус.
     *
     * @return PromocodeFilter Правила поиска.
     */
    public function filterApplicable(bool $status): self
    {
        if ($status) {
            return $this->applicable();
        }

        return $this->inapplicable();
    }
}
