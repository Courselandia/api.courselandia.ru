<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Http\Requests;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс проверки запроса для удаления документа.
 */
class DocumentDestroyRequest extends FormRequest
{
    /**
     * Получить правила валидации для запроса.
     *
     * @return array Правила валидирования.
     */
    #[ArrayShape(['id' => 'string', 'format' => 'string'])] public function rules(): array
    {
        return [
            'id' => 'required|integer|digits_between:1,20',
            'format' => 'required|in:jpg,png,gif,jpeg,swf,flw'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['id' => 'string', 'format' => 'string'])] public function attributes(): array
    {
        return [
            'id' => trans('document::http.requests.documentDestroy.id'),
            'format' => trans('document::http.requests.documentDestroy.format')
        ];
    }
}
