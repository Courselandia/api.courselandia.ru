<?php
/**
 * Котировки валют.
 * Пакет содержит классы для получения котировок валют.
 *
 * @package App.Models.Currency
 */

namespace App\Models\Currency;

use Carbon\Carbon;
use App\Models\Contracts\Currency;
use Orchestra\Parser\Xml\Facade as XmlParser;

/**
 * Класс драйвер для удаленного получения котировок с центробанка России.
 */
class CurrencyCbr extends Currency
{
    /**
     * Получение валюты по коду валюты.
     *
     * @param  Carbon  $carbon  Дата на которую нужно получить котировки.
     * @param  string  $charCode  Код валюты для получения котировки.
     *
     * @return float|null Стоимость запрашиваемой валюты.
     */
    public function get(Carbon $carbon, string $charCode): ?float
    {
        $pathToFile = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req='.$carbon->format('d.m.Y');
        $xml = XmlParser::load($pathToFile);

        if ($xml) {
            $result = $xml->parse([
                'values' => ['uses' => 'Valute[NumCode,CharCode,Nominal,Name,Value]']
            ]);

            if ($result && isset($result['values'])) {
                for ($i = 0; $i < count($result['values']); $i++) {
                    if ($result['values'][$i]['CharCode'] === $charCode) {
                        return (float)str_replace(',', '.', $result['values'][$i]['Value']);
                    }
                }
            }
        }

        return null;
    }
}
