<?php
/**
 * Контракты.
 * Этот пакет содержит контракты ядра системы.
 *
 * @package App.Models.Contracts
 */

namespace App\Models\Contracts;

/**
 * Абстрактный класс для проектирования собственной системы морфирования.
 */
abstract class Morph
{
    /**
     * Метод для получения отморфированного текста.
     *
     * @param  string|null  $value  Строка для морфирования.
     *
     * @return string|null Вернет отморфированный текст.
     */
    abstract public function get(string $value = null): ?string;
}
