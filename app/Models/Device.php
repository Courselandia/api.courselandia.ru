<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Определение устройства пользователя.
 */
class Device
{
    /**
     * Данные пользовательского агента.
     *
     * @var string|null
     */
    private ?string $agent = null;

    /**
     * Массив операционных систем.
     *
     * @var array
     */
    private const OPERATION_SYSTEMS = [
        '/windows phone 10/i' => 'Windows Phone 8',
        '/windows phone 8/i' => 'Windows Phone 8',
        '/windows phone os 7/i' => 'Windows Phone 7',
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    ];

    /**
     * Массив браузеров.
     *
     * @var array
     */
    private const BROWSERS = [
        '/edg/i' => 'Microsoft Edge',
        '/msie/i' => 'Internet Explorer',
        '/YaBrowser/i' => 'Yandex browser',
        '/Yptp/i' => 'Yandex browser',
        '/firefox/i' => 'Firefox',
        '/opera/i' => 'Opera',
        '/OPR/i' => 'Opera',
        '/chrome/i' => 'Chrome',
        '/safari/i' => 'Safari',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser',
        '/Trident/i' => 'Internet Explorer',
    ];

    /**
     * Конструктор.
     */
    public function __construct(?string $agent = null)
    {
        if (!$agent && isset($_SERVER['HTTP_USER_AGENT'])) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
        }

        if ($agent) {
            $this->setAgent($agent);
        }
    }

    /**
     * Установка агента.
     *
     * @param  string  $agent  Данные пользовательского агента.
     *
     * @return Device Возвращает текущий объект.
     */
    public function setAgent(string $agent): Device
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Получение агента.
     *
     * @return string|null Данные пользовательского агента.
     */
    public function getAgent(): ?string
    {
        return $this->agent;
    }

    /**
     * Получение операционной системы.
     *
     * @return string Вернет название операционной системы.
     */
    public function operationSystem(): string
    {
        $osPlatform = 'Unknown OS Platform';

        if ($this->getAgent()) {
            foreach (self::OPERATION_SYSTEMS as $regex => $value) {
                if (preg_match($regex, $this->getAgent())) {
                    return $value;
                }
            }
        }

        return $osPlatform;
    }

    /**
     * Получение типа устройства.
     *
     * @return string Вернет тип устройства.
     */
    public function system(): string
    {
        $device = 'SYSTEM';

        if ($this->getAgent()) {
            foreach (self::OPERATION_SYSTEMS as $regex => $value) {
                if (preg_match($regex, $this->getAgent())) {
                    return !preg_match('/(windows|mac|linux|ubuntu)/i', $value) ? 'MOBILE' : (preg_match(
                        '/phone/i',
                        $value
                    ) ? 'MOBILE' : 'SYSTEM');
                }
            }
        }

        return $device;
    }

    /**
     * Получение браузера.
     *
     * @return string Вернет название браузера.
     */
    public function browser(): string
    {
        $browser = 'Unknown Browser';

        if ($this->getAgent()) {
            foreach (self::BROWSERS as $regex => $value) {
                if (preg_match($regex, $this->getAgent())) {
                    $browser = $value;
                    break;
                }
            }
        }

        return $browser;
    }
}
