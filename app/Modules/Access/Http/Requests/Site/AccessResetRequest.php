<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Requests\Site;

use App\Models\FormRequest;

/**
 * Класс для изменения пароля пользователя.
 */
class AccessResetRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    public function rules(): array
    {
        return [
            'code' => 'required',
            'password' => 'required|between:4,25|confirmed',
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
            'code' => trans('access::http.requests.site.accessResetRequest.code'),
            'password' => trans('access::http.requests.site.accessResetRequest.password'),
        ];
    }
}
