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
 * Класс для проверки возможности изменения пароля пользователя.
 */
class AccessResetCheckRequest extends FormRequest
{
    /**
     * Возвращает правила проверки.
     *
     * @return array Массив правил проверки.
     */
    #[ArrayShape(['code' => 'string'])] public function rules(): array
    {
        return [
            'code' => 'required'
        ];
    }

    /**
     * Возвращает атрибуты.
     *
     * @return array Массив атрибутов.
     */
    #[ArrayShape(['code' => 'string'])] public function attributes(): array
    {
        return [
            'code' => trans('access::http.requests.site.accessResetCheckRequest.code')
        ];
    }
}
