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
use JetBrains\PhpStorm\ArrayShape;

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
    #[ArrayShape([
        'profession_id' => 'string',
        'level' => 'string',
        'salary' => 'string',
        'status' => 'string',
    ])] public function rules(): array
    {
        return [
            'profession_id' => 'exists:professions,id',
            'level' => 'in:' . implode(',', EnumList::getValues(Level::class)),
            'salary' => 'integer',
            'status' => 'boolean',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape([
        'profession_id' => 'string',
        'level' => 'string',
        'salary' => 'string',
        'status' => 'string',
    ])] public function attributes(): array
    {
        return [
            'profession_id' => trans('salary::http.requests.admin.salaryCreateRequest.professionId'),
            'level' => trans('salary::http.requests.admin.salaryCreateRequest.level'),
            'salary' => trans('salary::http.requests.admin.salaryCreateRequest.salary'),
            'status' => trans('salary::http.requests.admin.salaryCreateRequest.status'),
        ];
    }
}
