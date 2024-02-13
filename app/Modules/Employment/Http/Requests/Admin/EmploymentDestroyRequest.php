<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для удаления трудоустройства.
 */
class EmploymentDestroyRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'ids' => 'required|array',
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
            'ids' => trans('employment::http.requests.admin.employmentDestroyRequest.ids')
        ];
    }
}
