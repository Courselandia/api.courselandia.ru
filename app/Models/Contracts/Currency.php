<?php
/**
 * Контракты.
 * Этот пакет содержит контракты ядра системы.
 *
 * @package App.Models.Contracts
 */

namespace App\Models\Contracts;

use Carbon\Carbon;

/**
 * Абстрактный класс для проектирования собственной системы котировок.
 */
abstract class Currency
{
    /**
     * Получение валюты по коду валюты.
     *
     * @param  Carbon  $carbon  Дата на которую нужно получить котировки.
     * @param  string  $charCode  Код валюты для получения котировки. Если не указать, то вернет все валюты.
     *
     * @return float|null Стоимость запрашиваемой валюты.
     */
    abstract public function get(Carbon $carbon, string $charCode): ?float;
}
