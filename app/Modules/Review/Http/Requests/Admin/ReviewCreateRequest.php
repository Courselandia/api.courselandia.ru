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
use App\Modules\Review\Enums\Level;
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
        'level' => 'string',
    ])] public function rules(): array
    {
        return [
            'level' => 'in:'.implode(',' , EnumList::getValues(Level::class))
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'level' => 'string'
    ])] public function attributes(): array
    {
        return [
            'level' => trans('review::http.requests.admin.reviewCreateRequest.level'),
        ];
    }
}
