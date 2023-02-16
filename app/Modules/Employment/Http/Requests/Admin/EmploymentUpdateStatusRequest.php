<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для обновления статуса трудоустройства.
 */
class EmploymentUpdateStatusRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['status' => 'string'])] public function rules(): array
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
    #[ArrayShape(['status' => 'string'])] public function attributes(): array
    {
        return [
            'status' => trans('employment::http.requests.admin.employmentUpdateStatusRequest.status'),
        ];
    }
}
