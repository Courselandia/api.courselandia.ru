<?php
/**
 * Тестирование.
 * Пакет содержит классы для выполнения стандартных процедур тестирования.
 *
 * @package App.Models.Test
 */

namespace App\Models\Test;

use Facebook\WebDriver\Exception\ElementClickInterceptedException;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\UnknownErrorException;
use Facebook\WebDriver\WebDriverBy;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\Exception\TimeOutException;

/**
 * Класс помощи для тестирования UI административной системы.
 */
trait HelpAdminBrowser
{
    use AccessTest;

    /**
     * Авторизация.
     *
     * @param  Browser  $browser  Браузер.
     * @param  string  $module  Название модуля который использует этот метод.
     * @param  string  $method  Название метода который использует этот метод.
     *
     * @return Browser Вернет браузер.
     * @throws TimeOutException
     */
    public function getLogin(Browser $browser, string $module, string $method): Browser
    {
        return $browser
            ->deleteCookie('accessToken')
            ->deleteCookie('secret')
            ->deleteCookie('refreshToken')
            ->deleteCookie('remember')
            ->visit('/')
            ->screenshot($module.'_admin_'.$method.'_step_1')
            ->pause(1000 * 30)
            ->screenshot($module.'_admin_'.$method.'_step_2')
            ->type('INPUT[name=login]', $this->getAdmin('login'))
            ->type('INPUT[name=password]', $this->getAdmin('password'))
            ->screenshot($module.'_admin_'.$method.'_step_3')
            ->press('@login')
            ->screenshot($module.'_admin_'.$method.'_step_4')
            ->waitForLocation('/dashboard', 30)
            ->screenshot($module.'_admin_'.$method.'_step_5');
    }

    /**
     * Ввод в CKEDITOR.
     *
     * @param Browser $browser Браузер.
     * @param string $selector Селектор.
     * @param string $text Текст ввода.
     *
     * @return Browser Вернет браузер.
     * @throws UnknownErrorException
     */
    public function typeInCKEditor(Browser $browser, string $selector, string $text): Browser
    {
        $ckIframe = $browser->elements($selector)[0];
        $browser->driver->switchTo()->frame($ckIframe);
        $body = $browser->driver->findElement(WebDriverBy::xpath('//body'));
        $body->sendKeys($text);
        $browser->driver->switchTo()->defaultContent();

        return $browser;
    }

    /**
     * Ввод в автокомплекс.
     *
     * @param  Browser  $browser  Браузер.
     * @param  string  $selector  Селектор.
     * @param  int  $index  Индекс.
     *
     * @return Browser Вернет браузер.
     * @throws ElementClickInterceptedException|NoSuchElementException
     */
    public function selectAutocomplete(Browser $browser, string $selector, int $index = 0): Browser
    {
        $browser
            ->click($selector)
            ->pause(1000 * 5)
            ->elements('.menuable__content__active .v-list-item--link')[$index]->click();

        return $browser;
    }

    /**
     * Получить индекс пункта автокомплекса.
     *
     * @param  Browser  $browser  Браузер.
     * @param  string  $selector  Селектор.
     * @param  string  $value  Значение.
     *
     * @return int|null Вернет индекс.
     * @throws TimeOutException|ElementClickInterceptedException|NoSuchElementException
     */
    public function getSelectAutocompleteIndex(Browser $browser, string $selector, string $value): ?int
    {
        $elements = $browser
            ->click($selector)
            ->waitFor('.menuable__content__active')
            ->elements('.menuable__content__active .v-list-item--link');

        for ($i = 0; $i < count($elements); $i++) {
            if (trim($elements[$i]->getText()) == $value) {
                return $i;
            }
        }

        return null;
    }

    /**
     * Ввод в выбора даты.
     *
     * @param  Browser  $browser  Браузер.
     * @param  string  $selector  Селектор.
     * @param  int  $tr  Индекс положения даты по вертикали.
     * @param  int  $td  Индекс положения даты по горизонтали.
     *
     * @return Browser Вернет браузер.
     * @throws ElementClickInterceptedException|NoSuchElementException
     */
    public function selectDatePicker(Browser $browser, string $selector, int $tr = 2, int $td = 1): Browser
    {
        $browser
            ->click($selector)
            ->pause(1000 * 3)
            ->click(
                '.v-menu__content.menuable__content__active TABLE TBODY TR:nth-child('.$tr.') TD:nth-child('.$td.') BUTTON'
            );

        return $browser;
    }

    /**
     * Ввод в выбора времени.
     *
     * @param  Browser  $browser  Браузер.
     * @param  string  $selector  Селектор.
     * @param  int  $hours  Индекс положения даты по вертикали.
     * @param  int  $minutes  Индекс положения даты по горизонтали.
     * @param  bool  $selectMinutes  Выбрать только минуты.
     *
     * @return Browser Вернет браузер.
     * @throws ElementClickInterceptedException|NoSuchElementException
     */
    public function selectTimePicker(
        Browser $browser,
        string $selector,
        int $hours = 0,
        int $minutes = 0,
        bool $selectMinutes = false
    ): Browser {
        $browser
            ->click($selector)
            ->pause(1000 * 3);

        if (!$selectMinutes) {
            $browser->elements(
                '.v-time-picker-clock__container .v-time-picker-clock .v-time-picker-clock__inner .v-time-picker-clock__item'
            )[$hours]->click();
            $browser->pause(1000 * 3);
        }

        $browser->elements(
            '.v-time-picker-clock__container .v-time-picker-clock .v-time-picker-clock__inner .v-time-picker-clock__item'
        )[$minutes]->click();
        $browser->pause(1000 * 3);

        return $browser;
    }

    /**
     * Собственный метод очистки поля формы.
     *
     * @param  Browser  $browser  Браузер.
     * @param  string  $selector  Селектор.
     *
     * @return Browser Вернет браузер.
     */
    public function clear(Browser $browser, string $selector): Browser
    {
        $value = $browser->value($selector);
        $browser->keys($selector, ...array_fill(0, strlen($value), '{backspace}'));

        return $browser;
    }
}
