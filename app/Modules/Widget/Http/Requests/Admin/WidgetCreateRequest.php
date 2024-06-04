<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Http\Requests\Admin;

use App\Models\FormRequest;
use Illuminate\Support\Str;

/**
 * Класс запрос для создания виджета.
 */
class WidgetCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'status' => 'boolean',
            'values' => 'nullable',
            'values.*.name' => 'required|between:1,191',
            'values.*.value' => 'required',
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
            'status' => trans('widget::http.requests.admin.widgetCreateRequest.status'),
            'values.*.name' => trans('widget::http.requests.admin.widgetCreateRequest.name'),
            'values.*.value' => trans('widget::http.requests.admin.widgetCreateRequest.value'),
        ];
    }
}
