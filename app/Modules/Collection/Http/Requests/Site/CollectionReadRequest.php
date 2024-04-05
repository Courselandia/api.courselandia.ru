<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Http\Requests\Site;

use App\Models\FormRequest;

/**
 * Класс запрос для получения коллекций.
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
        return [
            'direction_id' => 'integer|digits_between:0,20|exists_soft:directions,id',
            'limit' => 'integer|digits_between:0,20',
            'offset' => 'integer|digits_between:0,20',
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
            'direction_id' => trans('collection::http.requests.site.collectionReadRequest.directionId'),
            'limit' => trans('collection::http.requests.site.collectionReadRequest.limit'),
            'offset' => trans('collection::http.requests.site.collectionReadRequest.offset'),
        ];
    }
}
