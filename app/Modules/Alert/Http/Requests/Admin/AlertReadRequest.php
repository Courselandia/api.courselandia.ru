<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для чтения предупреждений.
 */
class AlertReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'offset' => 'nullable|integer|digits_between:0,20',
            'limit' => 'nullable|integer|digits_between:0,20',
            'status' => 'nullable|bool',
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
            'offset' => trans('alert::http.requests.site.alertRead.offset'),
            'limit' => trans('alert::http.requests.site.alertRead.limit'),
            'status' => trans('alert::http.requests.site.alertRead.status')
        ];
    }
}
