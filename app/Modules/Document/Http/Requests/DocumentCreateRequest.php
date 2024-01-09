<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Http\Requests;

use App\Models\FormRequest;

/**
 * Класс проверки запроса для создания документа.
 */
class DocumentCreateRequest extends FormRequest
{
    /**
     * Получить правила валидации для запроса.
     *
     * @return array Правила валидирования.
     */
    public function rules(): array
    {
        return [
            'file' => 'required|document',
            'id' => 'required|integer|digits_between:1,20',
            'format' => 'required'
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
            'file' => trans('document::http.requests.documentCreate.file'),
            'id' => trans('document::http.requests.documentCreate.id'),
            'format' => trans('document::http.requests.documentCreate.format')
        ];
    }
}
