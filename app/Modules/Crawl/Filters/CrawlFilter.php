<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Filters;

use Config;
use App\Modules\Crawl\Enums\Engine;
use Carbon\Carbon;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы индексации.
 */
class CrawlFilter extends ModelFilter
{
    /**
     * Массив сопоставлений атрибутом поиска отношений с методом его реализации.
     *
     * @var array
     */
    public $relations = [
        'page' => [
            'page-path'  => 'pagePath',
            'page-lastmod'  => 'pageLastmod',
        ],
    ];

    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return $this Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('crawls.id', $id);
    }

    /**
     * Поиск по ID страницы.
     *
     * @param int|string $pageId ID страницы.
     *
     * @return self Правила поиска.
     */
    public function page(int|string $pageId): self
    {
        return $this->where('crawls.page_id', $pageId);
    }

    /**
     * Поиск по ID задачи.
     *
     * @param int|string $taskId ID задачи.
     *
     * @return self Правила поиска.
     */
    public function task(string $taskId): self
    {
        return $this->where('crawls.task_id', $taskId);
    }

    /**
     * Поиск по дате отправки на индексацию.
     *
     * @param array $dates Даты от и до.
     *
     * @return $this Правила поиска.
     */
    public function pushedAt(array $dates): self
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('crawls.pushed_at', $dates);
    }

    /**
     * Поиск по дате индексации.
     *
     * @param array $dates Даты от и до.
     *
     * @return $this Правила поиска.
     */
    public function crawledAt(array $dates): self
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('crawls.crawled_at', $dates);
    }

    /**
     * Поиск по поисковой системе.
     *
     * @param Engine[]|Engine|string $engines Поисковые системы.
     *
     * @return self Правила поиска.
     */
    public function engine(array|Engine|string $engines): self
    {
        return $this->whereIn('crawls.engine', is_array($engines) ? $engines : [$engines]);
    }

    /**
     * Поиск по профессии.
     *
     * @param string $search Строка поиска.
     *
     * @return self Правила поиска.
     */
    public function pagePath(string $search): self
    {
        return $this->related('page', function ($query) use ($search) {
            return $query->whereLike('pages.path', $search);
        });
    }

    /**
     * Поиск по дате обновления.
     *
     * @param array $dates Даты от и до.
     *
     * @return self Правила поиска.
     */
    public function pageLastmod(array $dates): self
    {
        return $this->related('page', function ($query) use ($dates) {
            $dates = [
                Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
                Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
            ];

            return $query->whereBetween('pages.lastmod', $dates);
        });
    }
}
