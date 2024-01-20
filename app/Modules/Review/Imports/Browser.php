<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Imports;

use Throwable;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\RemoteWebElement;
use Facebook\WebDriver\WebDriverBy;

/**
 * Класс браузера.
 */
class Browser
{
    /**
     * URL к сервису браузера.
     *
     * @var string
     */
    public string $baseUrl = 'http://browser:4444';

    /**
     * Драйвер браузера.
     *
     * @var RemoteWebDriver
     */
    private RemoteWebDriver $driver;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->driver = RemoteWebDriver::create($this->baseUrl, DesiredCapabilities::firefox());
    }

    /**
     * Получение драйвера.
     *
     * @return RemoteWebDriver
     */
    public function getDriver(): RemoteWebDriver
    {
        return $this->driver;
    }

    /**
     * Диструктор.
     */
    public function __destruct()
    {
        $this->getDriver()->quit();
    }

    /**
     * Получение элемента.
     *
     * @param RemoteWebDriver|RemoteWebElement $driver Элемент, в котором осуществляется поиск.
     * @param WebDriverBy $by Селектор.
     *
     * @return RemoteWebElement|null Вернет элемент.
     */
    public function findElementIfExists(RemoteWebDriver|RemoteWebElement $driver, WebDriverBy $by): RemoteWebElement|null
    {
        try {
            return $driver->findElement($by);
        } catch (Throwable) {
            return null;
        }
    }
}
