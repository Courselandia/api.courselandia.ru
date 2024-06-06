<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для обновления статуса виджета.
 */
class WidgetUpdateStatusRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'status' => 'required|boolean',
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
            'status' => trans('widget::http.requests.admin.widgetUpdateStatusRequest.status'),
        ];
    }
}
