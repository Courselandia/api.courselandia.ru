<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Review\Enums\Status;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для создания отзывов.
 */
class ReviewCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape([
        'status' => 'string',
    ])] public function rules(): array
    {
        return [
            'status' => 'in:'.implode(',' , EnumList::getValues(Status::class))
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'status' => 'string'
    ])] public function attributes(): array
    {
        return [
            'status' => trans('review::http.requests.admin.reviewCreateRequest.status'),
        ];
    }
}
