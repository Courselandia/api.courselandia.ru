<?php
/**
 * Валидирование.
 * Пакет содержит классы для расширения способов валидирования.
 *
 * @package App.Models.Validators
 */

namespace App\Models\Validators;

use Illuminate\Http\UploadedFile;

/**
 * Классы для валидации форматов медиа файлов (изображений разных форматов и видео).
 */
class MediaValidator
{
    /**
     * Валидация.
     *
     * @param  string|null  $attribute  Название атрибута.
     * @param  UploadedFile|string  $value  Значение для валидации.
     * @param  array  $parameters  Параметры.
     *
     * @return bool Вернет результат валидации.
     */
    public function validate(?string $attribute, mixed $value, array $parameters): bool
    {
        if (!$value instanceof UploadedFile) {
            return false;
        }

        if (
            count($parameters) &&
            in_array(
                strtolower($value->getClientOriginalExtension()),
                array_map('strtolower', $parameters)
            ))
        {
            return true;
        } elseif (!count($parameters)) {
            $parameters = [
                'jpg',
                'png',
                'gif',
                'webp',
                'svg',
                'mp4'
            ];

            return in_array(strtolower($value->getClientOriginalExtension()), array_map('strtolower', $parameters));
        }

        return false;
    }
}
