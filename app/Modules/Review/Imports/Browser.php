<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Review\Imports;

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

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
}
