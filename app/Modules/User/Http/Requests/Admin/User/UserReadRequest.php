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
    public function rules(): array
    {
        $columnsSort = Schema::getColumnListing('users');
        $columnsSort[] = 'role-name';
        $columnsSort = implode(',', $columnsSort);

        $columnsFilter = [
            'id',
            'login',
            'first_name',
            'second_name',
            'phone',
            'status',
            'created_at',
            'role-name'
        ];
        $columnsFilter = implode(',', $columnsFilter);

        return [
            'sorts' => 'array|sorts:' . $columnsSort,
            'offset' => 'integer|digits_between:0,20',
            'limit' => 'integer|digits_between:0,20',
            'filters' => 'array|filters:' . $columnsFilter . '|filter_date_range:created_at',
            'filters.status' => 'boolean',
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
            'sorts' => trans('user::http.requests.admin.user.userReadRequest.sorts'),
            'offset' => trans('user::http.requests.admin.user.userReadRequest.offset'),
            'limit' => trans('user::http.requests.admin.user.userReadRequest.limit'),
            'filters' => trans('user::http.requests.admin.user.userReadRequest.filters'),
            'filters.status' => trans('category::http.requests.admin.categoryReadRequest.status'),
        ];
    }
}
