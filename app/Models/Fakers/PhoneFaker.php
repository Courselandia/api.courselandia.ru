<?php
/**
 * Генераторы случайных данных.
 * Пакет содержит классы для классов генераторов случайных данных.
 *
 * @package App.Models.Fakers
 */

namespace App\Models\Fakers;

use Faker\Provider\Base;

/**
 * Класс для создания случайного номера телефона.
 */
class PhoneFaker extends Base
{
    /**
     * Метод для получения телефона
     *
     * @param  int  $code  Код страны.
     *
     * @return string Вернет номер телефона.
     */
    public function phone(int $code = 1): string
    {
        return '+'.$code.'-'.rand(100, 999).'-'.rand(100, 999).'-'.rand(1000, 9999);
    }
}
