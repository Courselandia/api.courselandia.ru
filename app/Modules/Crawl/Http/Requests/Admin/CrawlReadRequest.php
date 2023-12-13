<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Http\Requests\Admin;

use App\Models\FormRequest;
use App\Modules\Crawl\Enums\Engine;
use Schema;
use App\Models\Enums\EnumList;

/**
 * Класс запрос для чтения индексаций.
 */
class CrawlReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('crawls');

        $columnsSort = array_merge(
            $columns,
            [
                'page-path',
                'page-lastmod',
            ]
        );

        $columnsFilter = array_merge(
            $columns,
            [
                'page-path',
                'page-lastmod',
            ]
        );

        return [
            'sorts' => 'array|sorts:' . implode(',', $columnsSort),
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . implode(',', $columnsFilter) . '|filter_date_range:lastmod|filter_date_range:pushed_at|filter_date_range:crawled_at',
            'filters.engine' => 'in:' . implode(',', EnumList::getValues(Engine::class)),
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    public function attributes(): array
    {
        return [
            'sorts' => trans('crawl::http.requests.admin.crawlReadRequest.sorts'),
            'offset' => trans('crawl::http.requests.admin.crawlReadRequest.offset'),
            'limit' => trans('crawl::http.requests.admin.crawlReadRequest.limit'),
            'filters' => trans('crawl::http.requests.admin.crawlReadRequest.filters'),
        ];
    }
}
