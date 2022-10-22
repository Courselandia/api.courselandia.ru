<?php
/**
 * Тестирование ядра базовых классов.
 * Этот пакет содержит набор тестов для ядра базовых классов.
 *
 * @package App.Test.Models
 */

namespace App\Tests\Models;

use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;
use Util;

/**
 * Тестирование: Класс работы с утилитами.
 */
class UtilTest extends TestCase
{
    use CreatesApplication;

    /**
     * Конвертирование из одной кодировки в другую.
     *
     * @return void
     */
    public function testIconv(): void
    {
        $result = Util::iconv('test');
        $this->assertEquals('test', $result);
    }

    /**
     * Очистка строки от всех HTML тегов.
     *
     * @return void
     */
    public function testGetText(): void
    {
        $result = Util::getText('<b>test<br />here</b>');
        $this->assertEquals('testhere', $result);
    }

    /**
     * Очистка строки с переводом тега &lt;br /&gt; к \\r\\n и удаление HTML разметки.
     *
     * @return void
     */
    public function testGetTextN(): void
    {
        $result = Util::getTextN('<b>test<br />here</b>');
        $this->assertEquals("test\r\nhere", $result);
    }

    /**
     * Очистка строки с переводом каретки к тэгу &lt;br /&gt; и удаление HTML разметки.
     *
     * @return void
     */
    public function testGetTextBr(): void
    {
        $result = Util::getTextBr("<b>test\r\nhere</b>");
        $this->assertEquals('test<br />here', $result);
    }

    /**
     * Очистка строки с переводом каретки к тэгу &lt;br /&gt; с сохранением HTML разметки.
     *
     * @return void
     */
    public function testGetHtmlBr(): void
    {
        $result = Util::getHtmlBr("<b>test\r\nhere</b>");
        $this->assertEquals('<b>test<br />here</b>', $result);
    }

    /**
     * Очистка строки с сохранением HTML разметки.
     *
     * @return void
     */
    public function testGetHtmlN(): void
    {
        $result = Util::getHtmlN('<b>test<br >here</b>');
        $this->assertEquals('<b>test<br >here</b>', $result);
    }

    /**
     * Обработка строки с переводом тега &lt;br /&gt; к \\r\\n.
     *
     * @return void
     */
    public function testParserBrToRn(): void
    {
        $result = Util::parserBrToRn('test<br />here');
        $this->assertEquals("test\r\nhere", $result);
    }

    /**
     * Обработка строки с переводом каретки к тэгу &lt;br /&gt;.
     *
     * @return void
     */
    public function testParserRnToBr(): void
    {
        $result = Util::parserRnToBr("test\r\nhere");
        $this->assertEquals('test<br />here', $result);
    }

    /**
     * Удаление всех лишних пробелов в строке.
     *
     * @return void
     */
    public function testDeleteWhitespace(): void
    {
        $result = Util::deleteWhitespace(' test  here ');
        $this->assertEquals('test here', $result);
    }

    /**
     * Транслирует текст.
     *
     * @return void
     */
    public function testLatin(): void
    {
        $result = Util::latin('Тестирую');
        $this->assertEquals('Testiruu', $result);
    }

    /**
     * Метод проверит является ли массив ассоциативным.
     *
     * @return void
     */
    public function testIsAssoc(): void
    {
        $result = Util::isAssoc([
            'test' => 1
        ]);

        $this->assertTrue($result);
    }

    /**
     * Получение отформатированного числа.
     *
     * @return void
     */
    public function testGetNumber(): void
    {
        $result = Util::getNumber(123.12, 2);
        $this->assertEquals(123.12, $result);
    }

    /**
     * Получение отформатированного числа в виде цены.
     *
     * @return void
     */
    public function testGetMoney(): void
    {
        $result = Util::getMoney(123.12);
        $this->assertEquals('$123.12', $result);
    }
}
