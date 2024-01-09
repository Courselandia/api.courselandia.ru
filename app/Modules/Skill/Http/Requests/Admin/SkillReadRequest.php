<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Http\Requests\Admin;

use App\Models\FormRequest;
use Schema;

/**
 * Класс запрос для чтения навыков.
 */
class SkillReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        $columns = Schema::getColumnListing('skills');
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
            'sorts' => trans('skill::http.requests.admin.skillReadRequest.sorts'),
            'offset' => trans('skill::http.requests.admin.skillReadRequest.offset'),
            'limit' => trans('skill::http.requests.admin.skillReadRequest.limit'),
            'filters' => trans('skill::http.requests.admin.skillReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
