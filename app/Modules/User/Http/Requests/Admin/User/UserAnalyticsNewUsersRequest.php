<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Http\Requests\Admin\User;

use App\Models\Enums\DateGroup;
use App\Models\Enums\DatePeriod;
use App\Models\Enums\EnumList;
use App\Models\FormRequest;

/**
 * Класс запрос для чтения статистики новых пользователей на сайте.
 */
class UserAnalyticsNewUsersRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'group' => 'nullable|in:' . implode(',' , EnumList::getValues(DateGroup::class)),
            'datePeriod' => 'nullable|in:' . implode(',' , EnumList::getValues(DatePeriod::class)),
            'dateFrom' => 'nullable|date_format:Y-m-d',
            'dateTo' => 'nullable|date_format:Y-m-d'
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
            'group' => trans('user::http.requests.admin.userAnalytics.userAnalyticsNewUsersRequest.group'),
            'datePeriod' => trans('user::http.requests.admin.userAnalytics.userAnalyticsNewUsersRequest.datePeriod'),
            'dateFrom' => trans('user::http.requests.admin.userAnalytics.userAnalyticsNewUsersRequest.dateFrom'),
            'dateTo' => trans('user::http.requests.admin.userAnalytics.userAnalyticsNewUsersRequest.dateTo')
        ];
    }
}
