<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Класс работы с утилитами.
 * Этот класс содержит небольшие методы, которые часто требуются для выполнения тривиальных задач.
 */
class Util
{
    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @param  mixed  $arString  Переменная со строками.
     * @param  string  $from  Из кодировки.
     * @param  string  $to  В кодировку.
     *
     * @return mixed Конвертированная строка.
     */
    public static function iconv(mixed $arString, string $from = 'utf-8', string $to = 'windows-1251'): mixed
    {
        if (is_array($arString)) {
            foreach ($arString as $k => $v) {
                if (is_array($v) === true) {
                    $arString[$k] = self::iconv($v, $from, $to);
                } else {
                    $v = @iconv($from, $to, $arString[$k]);

                    if ($v !== false) {
                        $arString[$k] = $v;
                    }
                }
            }
        } else {
            $arString = @iconv($from, $to, $arString);
        }

        return $arString;
    }

    /**
     * Очистка строки от всех HTML тегов.
     *
     * @param  string  $string  Строка.
     *
     * @return string Очищенная строка.
     */
    public static function getText(string $string): string
    {
        $string = trim($string);

        return strip_tags($string);
    }

    /**
     * Очистка строки с переводом тега &lt;br /&gt; к \\r\\n и удаление HTML разметки.
     *
     * @param  string  $string  Строка.
     *
     * @return string Очищенная строка.
     */
    public static function getTextN(string $string): string
    {
        $string = trim($string);
        $string = self::parserBrToRn($string);

        return strip_tags($string);
    }

    /**
     * Очистка строки с переводом каретки к тэгу &lt;br /&gt; и удаление HTML разметки.
     *
     * @param  string  $string  Строка.
     *
     * @return string Очищенная строка.
     */
    public static function getTextBr(string $string): string
    {
        $string = trim($string);
        $string = strip_tags($string, '<br>,<br/>,<br />');

        return self::parserRnToBr($string);
    }

    /**
     * Очистка строки с переводом каретки к тэгу &lt;br /&gt; с сохранением HTML разметки.
     *
     * @param  string  $string  Строка.
     *
     * @return string Очищенная строка.
     */
    public static function getHtmlBr(string $string): string
    {
        $string = trim($string);

        return self::parserRnToBr($string);
    }

    /**
     * Очистка строки с сохранением HTML разметки.
     *
     * @param  string  $string  Строка.
     *
     * @return string Очищенная строка.
     */
    public static function getHtmlN(string $string): string
    {
        return trim($string);
    }

    /**
     * Обработка строки с переводом тега &lt;br /&gt; к \\r\\n.
     *
     * @param  string  $string  Строка.
     *
     * @return string Очищенная строка.
     */
    public static function parserBrToRn(string $string): string
    {
        $string = str_replace('<br />', "\r\n", $string);

        return str_replace('<br>', "\r\n", $string);
    }

    /**
     * Обработка строки с переводом каретки к тэгу &lt;br /&gt;.
     *
     * @param  string  $str  Строка.
     *
     * @return string Очищенная строка.
     */
    public static function parserRnToBr(string $str): string
    {
        $str = str_replace("\r\n", '<br />', $str);
        $str = str_replace("\n", '<br />', $str);

        return str_replace("\r", '<br />', $str);
    }

    /**
     * Удаление всех лишних пробелов в строке.
     *
     * @param  string  $string  Строка для очистки лишних пробелов.
     *
     * @return string Строка без лишних пробелов.
     */
    public static function deleteWhitespace(string $string): string
    {
        $string = preg_replace('/ {2,}/', ' ', $string);

        return trim($string);
    }

    /**
     * Транслирует текст.
     * Переводит текст с русского языка.
     *
     * @param  string  $string  Строка для перевода.
     * @param  string  $separator  Сепаратор, который используется в качестве пробела.
     * @param  bool  $symbols  Если указать true, то допустит только буквы и и цифры, остальные символы будут удалены.
     *
     * @return string Транслируемая строка.
     */
    public static function latin(string $string, string $separator = '-', bool $symbols = true): string
    {
        $order = array(
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'ё' => 'e',
            'ж' => 'zh',
            'з' => 'z',
            'и' => 'i',
            'й' => 'y',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ч' => 'ch',
            'ш' => 'sh',
            'щ' => 'sh',
            'ъ' => '',
            'ы' => 'i',
            'ь' => '',
            'э' => 'e',
            'ю' => 'u',
            'я' => 'ya',

            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'Ё' => 'E',
            'Ж' => 'ZH',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'Y',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ч' => 'CH',
            'Ш' => 'SH',
            'Щ' => 'SH',
            'Ъ' => '',
            'Ы' => 'I',
            'Ь' => '',
            'Э' => 'E',
            'Ю' => 'U',
            'Я' => 'Ya',

            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'd' => 'd',
            'e' => 'e',
            'f' => 'f',
            'g' => 'g',
            'h' => 'h',
            'i' => 'i',
            'j' => 'j',
            'k' => 'k',
            'l' => 'l',
            'm' => 'm',
            'n' => 'n',
            'o' => 'o',
            'p' => 'p',
            'q' => 'q',
            'r' => 'r',
            's' => 's',
            't' => 't',
            'u' => 'u',
            'v' => 'v',
            'w' => 'w',
            'x' => 'x',
            'y' => 'y',
            'z' => 'z',

            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
            'E' => 'E',
            'F' => 'F',
            'G' => 'G',
            'H' => 'H',
            'I' => 'I',
            'J' => 'J',
            'K' => 'K',
            'L' => 'L',
            'M' => 'M',
            'N' => 'N',
            'O' => 'O',
            'P' => 'P',
            'Q' => 'Q',
            'R' => 'R',
            'S' => 'S',
            'T' => 'T',
            'U' => 'U',
            'V' => 'V',
            'W' => 'W',
            'X' => 'X',
            'Y' => 'Y',
            'Z' => 'Z',

            '0' => '0',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',

            ' ' => $separator,
            $separator => $separator
        );

        $length = strlen($string);
        $latin = '';

        for ($i = 0; $i < $length; $i++) {
            $letter = mb_substr($string, $i, 1, 'utf-8');

            if (isset($order[$letter])) {
                $latin .= $order[$letter];
            } elseif ($symbols === false) {
                $latin .= $letter;
            }
        }

        return $latin;
    }

    /**
     * Метод проверит, является ли массив ассоциативным.
     *
     * @param  mixed  $arr  Ассоциативный массив для проверки.
     *
     * @return bool Возвращает true, если массив ассоциативный.
     */
    public static function isAssoc(mixed $arr): bool
    {
        if (array() === $arr || !is_array($arr)) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Получение уникального ключа.
     *
     * @param  array  $params  Параметры.
     *
     * @return string Вернет уникальный ключ.
     */
    public static function getKey(...$params): string
    {
        return md5(serialize($params));
    }

    /**
     * Получение отформатированного числа.
     *
     * @param  float  $number  Число для форматирования.
     * @param  int  $digits  Количество чисел после дробной точки.
     * @param  string  $separate  Точка для дробного числа.
     * @param  string  $separateDigits  Разделитель для числа.
     *
     * @return string Вернет отформатированное число.
     */
    private static function _number(
        float $number,
        int $digits = 0,
        string $separate = ',',
        string $separateDigits = '.'
    ): string {
        $numberArr = [];
        $number = round($number, $digits);

        if ($number < 0) {
            $minus = true;
        } else {
            $minus = false;
        }

        if ($minus) {
            $number = str_replace('-', '', $number) * 1;
        }

        $celAndOst = explode('.', $number);
        $numberArr[0] = $celAndOst[0];

        if (isset($celAndOst[1])) {
            $numberArr[1] = $celAndOst[1];
        }

        $lenPrice = strlen(trim($numberArr[0])) - 1;

        if ($lenPrice >= 3) {
            $numberForm = '';

            for ($i = $lenPrice, $z = -1; $i >= 0; $i--) {
                if ($z == 2) {
                    $numberForm = $separate.$numberForm;
                    $z = -1;
                }

                $numberForm = @$numberArr[0][$i].$numberForm;
                $z++;
            }

            $numberArr[0] = $numberForm;
        }

        $numberNew = $numberArr[0];

        if ($numberArr[0] != '' && isset($numberArr[1]) && $numberArr[1] != '' && $digits) {
            $numberNew = $numberArr[0].$separateDigits.$numberArr[1];
        }

        if ($minus) {
            return '-'.$numberNew;
        } else {
            return $numberNew;
        }
    }

    /**
     * Получение отформатированного числа.
     *
     * @param  float  $number  Число для форматирования.
     * @param  int  $digits  Количество чисел после дробной точки.
     *
     * @return string Вернет отформатированное число.
     */
    public static function getNumber(float $number, int $digits = 0): string
    {
        return self::_number($number, $digits);
    }

    /**
     * Получение отформатированного числа в виде цены.
     *
     * @param  float  $number  Число для форматирования.
     * @param  bool  $digits  Отображать дробные числа.
     * @param  string  $label  Знак валюты.
     * @param  bool  $beginning  Ставить ли знак валюты в начале.
     *
     * @return string Вернет отформатированное число.
     */
    public static function getMoney(
        float $number,
        bool $digits = true,
        string $label = '$',
        bool $beginning = true
    ): string {
        $digits = $digits === false ? 0 : 2;
        $money = self::_number($number, $digits);

        if ($label) {
            if ($beginning) {
                $money = $label.$money;
            } else {
                $money = $money.' '.$label;
            }
        }

        return $money;
    }

    /**
     * Проверка содержит ли строка корректный JSON.
     *
     * @param  string  $string  Строка проверки.
     *
     * @return bool Вернет результат проверки.
     */
    public static function isJson(string $string): bool
    {
        if (!is_numeric($string)) {
            json_decode($string);
            return (json_last_error() === JSON_ERROR_NONE);
        }

        return false;
    }
}
