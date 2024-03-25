<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Http\Requests\Admin\Collection;

use App\Models\FormRequest;

/**
 * Класс запрос для создания коллекции.
 */
class CollectionCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'image' => 'nullable|file|media:jpg,png,gif,webp,svg',
            'amount' => 'nullable|digits_between:0,5',
            'status' => 'boolean',
            'filters.*.name' => 'required|between:1,191',
            'filters.*.value' => 'required|json',
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
            'image' => trans('collection::http.requests.admin.collectionCreateRequest.image'),
            'amount' => trans('collection::http.requests.admin.collectionCreateRequest.amount'),
            'status' => trans('collection::http.requests.admin.collectionCreateRequest.status'),
            'filters.*.name' => trans('collection::http.requests.admin.collectionCreateRequest.filters'),
            'filters.*.value' => trans('collection::http.requests.admin.collectionCreateRequest.filters'),
        ];
    }
}
