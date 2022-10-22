<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Requests\Admin\User;

use Schema;
use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс запрос для чтения пользователей.
 */
class UserReadRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['sorts' => 'string', 'offset' => 'string', 'limit' => 'string', 'filters' => 'string'])] public function rules(): array
    {
        $columnSorts = Schema::getColumnListing('users');
        $columnSorts[] = 'role-name';
        $columnSorts = implode(',', $columnSorts);

        $columnFilters = [
            'id',
            'login',
            'first_name',
            'second_name',
            'phone',
            'status',
            'created_at',
            'role-name'
        ];
        $columnFilters = implode(',', $columnFilters);

        return [
            'sorts' => 'array|sorts:'.$columnSorts,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:'.$columnFilters.'|filter_date_range:created_at',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['sorts' => 'string', 'offset' => 'string', 'limit' => 'string', 'filters' => 'string'])] public function attributes(): array
    {
        return [
            'sorts' => trans('user::http.requests.admin.user.userReadRequest.sorts'),
            'offset' => trans('user::http.requests.admin.user.userReadRequest.offset'),
            'limit' => trans('user::http.requests.admin.user.userReadRequest.limit'),
            'filters' => trans('user::http.requests.admin.user.userReadRequest.filters'),
        ];
    }
}
