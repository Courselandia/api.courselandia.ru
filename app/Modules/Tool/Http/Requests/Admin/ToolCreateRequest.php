<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для создания инструмента.
 */
class ToolCreateRequest extends FormRequest
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
            'status' => trans('tool::http.requests.admin.toolCreateRequest.status')
        ];
    }
}
