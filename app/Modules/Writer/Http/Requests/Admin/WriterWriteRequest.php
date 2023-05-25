<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Http\Requests\Admin;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания учителя.
 */
class WriterWriteRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'request' => 'string',
    ])] public function rules(): array
    {
        return [
            'request' => 'max:30000',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'request' => 'string',
    ])] public function attributes(): array
    {
        return [
            'request' => trans('writer::http.requests.admin.writerWriteRequest.request'),
        ];
    }
}
