<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;

/**
 * Класс запрос для создания промокодов.
 */
class PromocodeCreateRequest extends FormRequest
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
            'min_price' => 'nullable|float',
            'discount' => 'float',
            'discount_type' => 'required|in:' . implode(',', EnumList::getValues(DiscountType::class)),
            'type' => 'required|in:' . implode(',', EnumList::getValues(Type::class)),
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
            'status' => trans('promocode::http.requests.admin.promocodeCreateRequest.status'),
            'date_start' => trans('promocode::http.requests.admin.promocodeCreateRequest.dateStart'),
            'date_end' => trans('promocode::http.requests.admin.promocodeCreateRequest.dateEnd'),
            'school_id' => trans('promocode::http.requests.admin.promocodeCreateRequest.schoolId'),
            'min_price' => trans('promocode::http.requests.admin.promocodeCreateRequest.minPrice'),
            'discount' => trans('promocode::http.requests.admin.promocodeCreateRequest.discount'),
            'discount_type' => trans('promocode::http.requests.admin.promocodeCreateRequest.discountType'),
            'type' => trans('promocode::http.requests.admin.promocodeCreateRequest.type'),
        ];
    }
}
