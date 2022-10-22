<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as FormRequestNative;

/**
 * Класс формы проверки запроса.
 */
class FormRequest extends FormRequestNative
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  Validator  $validator
     *
     * @return void
     * @throws FormRequestValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new FormRequestValidationException($this, $validator);
    }
}
