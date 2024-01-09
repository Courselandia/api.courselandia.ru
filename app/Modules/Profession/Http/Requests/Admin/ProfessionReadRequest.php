<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения профессий.
 */
class ProfessionReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('professions');
        $columns = implode(',', $columns);

        return [
            'sorts' => 'array|sorts:' . $columns,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columns . '|filter_date_range:published_at',
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
            'sorts' => trans('profession::http.requests.admin.professionReadRequest.sorts'),
            'offset' => trans('profession::http.requests.admin.professionReadRequest.offset'),
            'limit' => trans('profession::http.requests.admin.professionReadRequest.limit'),
            'filters' => trans('profession::http.requests.admin.professionReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
