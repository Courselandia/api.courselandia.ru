<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Http\Requests;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс проверки запроса для удаления изображения.
 */
class ImageDestroyRequest extends FormRequest
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
            'id' => trans('image::http.requests.imageDestroy.id'),
            'format' => trans('image::http.requests.imageDestroy.format')
        ];
    }
}
