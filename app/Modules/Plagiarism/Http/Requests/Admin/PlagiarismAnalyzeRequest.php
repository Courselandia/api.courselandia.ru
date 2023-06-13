<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания учителя.
 */
class PlagiarismAnalyzeRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'text' => 'string',
    ])] public function rules(): array
    {
        return [
            'text' => 'required|max:150000',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'text' => 'string',
    ])] public function attributes(): array
    {
        return [
            'text' => trans('plagiarism::http.requests.admin.plagiarismAnalyzeRequest.text'),
        ];
    }
}
