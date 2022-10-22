<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Requests\Site;

use App\Models\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс для отправки email для восстановления пароля.
 */
class AccessForgetRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['login' => 'string'])] public function rules(): array
    {
        return [
            'login' => 'required|between:1,199',
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['login' => 'string'])] public function attributes(): array
    {
        return [
            'login' => trans('access::http.requests.site.accessForgetRequest.login')
        ];
    }
}
