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
 * Класс для авторизации и регистрации пользователя через социальные сети.
 */
class AccessSocialRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['uid' => 'string', 'social' => 'string', 'login' => 'string'])] public function rules(): array
    {
        return [
            'uid' => 'required',
            'social' => 'required',
            'login' => 'required|between:1,199'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['uid' => 'string', 'social' => 'string', 'login' => 'string'])] public function attributes(): array
    {
        return [
            'uid' => trans('access::http.requests.site.accessSocialRequest.uid'),
            'social' => trans('access::http.requests.site.accessSocialRequest.social'),
            'login' => trans('access::http.requests.site.accessSocialRequest.login'),
        ];
    }
}
