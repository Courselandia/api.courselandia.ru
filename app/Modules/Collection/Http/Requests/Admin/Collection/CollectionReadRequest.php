<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Http\Requests\Admin\Collection;

use Schema;
use App\Models\FormRequest;

/**
 * Класс запрос для чтения коллекции.
 */
class CollectionReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('collections');
        $columns = implode(',', $columns);

        return [
            'sorts' => 'array|sorts:' . $columns,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columns,
            'filters.status' => 'boolean',
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
            'sorts' => trans('collection::http.requests.admin.collectionReadRequest.sorts'),
            'offset' => trans('collection::http.requests.admin.collectionReadRequest.offset'),
            'limit' => trans('collection::http.requests.admin.collectionReadRequest.limit'),
            'filters' => trans('collection::http.requests.admin.collectionReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
