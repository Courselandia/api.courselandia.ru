<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для удаления инструмента.
 */
class ToolDestroyRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['ids' => 'string'])] public function rules(): array
    {
        return [
            'ids' => 'required|json|ids'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['ids' => 'string'])] public function attributes(): array
    {
        return [
            'ids' => trans('tool::http.requests.admin.toolDestroyRequest.ids')
        ];
    }
}
