<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для обновления статуса объяснения как проходит обучение.
 */
class ProcessUpdateStatusRequest extends FormRequest
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
            'status' => trans('process::http.requests.admin.processUpdateStatusRequest.status'),
        ];
    }
}
