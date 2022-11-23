<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Http\Requests\Admin\Publication;

use Schema;
use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для чтения публикаций.
 */
class PublicationReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
    ])] public function rules(): array
    {
        $columnsSorts = Schema::getColumnListing('publications');
        $columnsSorts = implode(',', $columnsSorts);

        $columnsFilters = [
            'id',
            'published_at',
            'header',
            'link',
            'anons',
            'article',
            'status',
        ];
        $columnsFilters = implode(',', $columnsFilters);

        return [
            'sorts' => 'array|sorts:' . $columnsSorts,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columnsFilters . '|filter_date_range:published_at',
            'filters.status' => 'boolean',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'sorts' => 'string',
        'offset' => 'string',
        'limit' => 'string',
        'filters' => 'string',
        'filters.status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'sorts' => trans('publication::http.requests.admin.publicationReadRequest.sorts'),
            'offset' => trans('publication::http.requests.admin.publicationReadRequest.offset'),
            'limit' => trans('publication::http.requests.admin.publicationReadRequest.limit'),
            'filters' => trans('publication::http.requests.admin.publicationReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
