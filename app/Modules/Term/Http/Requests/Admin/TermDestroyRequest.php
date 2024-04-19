<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для удаления термина.
 */
class TermDestroyRequest extends FormRequest
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
            'ids' => trans('term::http.requests.admin.termDestroyRequest.ids')
        ];
    }
}
