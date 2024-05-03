<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Http\Requests\Admin;

use App\Models\FormRequest;

/**
 * Класс запрос для создания промоакций.
 */
class PromotionCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'status' => 'boolean',
            'date_start' => 'required|date_format:Y-m-d O',
            'date_end' => 'required|date_format:Y-m-d O',
            'school_id' => 'exists_soft:schools,id',
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
            'status' => trans('promotion::http.requests.admin.promotionCreateRequest.status'),
            'date_start' => trans('promotion::http.requests.admin.promotionCreateRequest.dateStart'),
            'date_end' => trans('promotion::http.requests.admin.promotionCreateRequest.dateEnd'),
            'school_id' => trans('promotion::http.requests.admin.promotionCreateRequest.schoolId'),
        ];
    }
}
