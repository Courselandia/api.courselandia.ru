<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Filters;

use Config;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы промоакций.
 */
class PromotionFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return PromotionFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('promotions.id', $id);
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
        return $this->whereIn('promotions.school_id', is_array($schoolIds) ? $schoolIds : [$schoolIds]);
    }

    /**
     * Поиск по дате статьи.
     *
     * @param array $dates Даты от и до.
     *
     * @return PromotionFilter Правила поиска.
     */
    public function date(array $dates): self
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->where('promotions.date_start', '>=', $dates[0])
            ->where('promotions.date_start', '<=', $dates[1]);
    }

    /**
     * Поиск по названию.
     *
     * @param string $query Строка поиска.
     *
     * @return PromotionFilter Правила поиска.
     */
    public function title(string $query): self
    {
        return $this->whereLike('promotions.title', $query);
    }

    /**
     * Поиск по оптсанию.
     *
     * @param string $query Строка поиска.
     *
     * @return PromotionFilter Правила поиска.
     */
    public function description(string $query): self
    {
        return $this->whereLike('promotions.link', $query);
    }

    /**
     * Поиск по началу действия промоакции.
     *
     * @param string $date Дата.
     *
     * @return PromotionFilter Правила поиска.
     */
    public function dateStart(string $date): self
    {
        return $this->where('promotions.date_start', '>=', Carbon::createFromFormat('Y-m-d O', $date)->startOfDay()->setTimezone(Config::get('app.timezone')));
    }

    /**
     * Поиск по окончания действия промоакции.
     *
     * @param string $date Дата.
     *
     * @return PromotionFilter Правила поиска.
     */
    public function dateEnd(string $date): self
    {
        return $this->where('promotions.date_end', '>=', Carbon::createFromFormat('Y-m-d O', $date)->endOfDay()->setTimezone(Config::get('app.timezone')));
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return PromotionFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('promotions.status', $status);
    }
}
