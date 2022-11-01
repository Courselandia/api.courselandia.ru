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
            'level' => trans('salary::http.requests.admin.salaryCreateRequest.level'),
        ];
    }
}
