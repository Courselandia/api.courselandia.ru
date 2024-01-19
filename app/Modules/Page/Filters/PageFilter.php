<?php
/**
 * Модуль Страницы.
 * Этот модуль содержит все классы для работы со списком страниц.
 *
 * @package App\Modules\Page
 */

namespace App\Modules\Page\Filters;

use Config;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы страниц.
 */
class PageFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return PageFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('pages.id', $id);
    }

    /**
     * Поиск по пути к странице.
     *
     * @param string $query Строка поиска.
     *
     * @return PageFilter Правила поиска.
     */
    public function path(string $query): self
    {
        return $this->whereLike('page.path', $query);
    }

    /**
     * Поиск по дате статьи.
     *
     * @param array $dates Даты от и до.
     *
     * @return PageFilter Правила поиска.
     */
    public function lastmod(array $dates): self
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('pages.lastmod', $dates);
    }
}
