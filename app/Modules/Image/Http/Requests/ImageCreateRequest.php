<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Http\Requests;

use App\Models\FormRequest;

/**
 * Класс проверки запроса для создания изображения.
 */
class ImageCreateRequest extends FormRequest
{
    /**
     * Получить правила валидации для запроса.
     *
     * @return array Правила валидирования.
     */
    public function rules(): array
    {
        return [
            'file' => 'required|image',
            'id' => 'required|integer|digits_between:1,20',
            'format' => 'required|in:jpg,png,gif,jpeg,swf,flw'
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
            'file' => trans('image::http.requests.imageCreate.file'),
            'id' => trans('image::http.requests.imageCreate.id'),
            'format' => trans('image::http.requests.imageCreate.format')
        ];
    }
}
