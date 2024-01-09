<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Http\Requests\Admin;

use App\Models\Enums\EnumList;
use App\Models\FormRequest;
use App\Modules\Salary\Enums\Level;

/**
 * Класс запрос для создания зарплаты.
 */
class SalaryCreateRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'profession_id' => 'exists_soft:professions,id',
            'level' => 'required|in:' . implode(',', EnumList::getValues(Level::class)),
            'salary' => 'integer',
            'status' => 'boolean',
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
            'profession_id' => trans('salary::http.requests.admin.salaryCreateRequest.professionId'),
            'level' => trans('salary::http.requests.admin.salaryCreateRequest.level'),
            'salary' => trans('salary::http.requests.admin.salaryCreateRequest.salary'),
            'status' => trans('salary::http.requests.admin.salaryCreateRequest.status'),
        ];
    }
}
