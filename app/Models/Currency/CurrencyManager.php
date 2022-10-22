<?php
/**
 * Котировки валют.
 * Пакет содержит классы для получения котировок валют.
 *
 * @package App.Models.Currency
 */

namespace App\Models\Currency;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы котировок для получения курсов валют.
 */
class CurrencyManager extends Manager
{
    public function getDefaultDriver()
    {
        return Config::get('currency.driver');
    }
}
