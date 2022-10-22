<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Класс помогающий определить это бот или нет.
 */
class Bot
{
    /**
     * Массив возможных движков ботов.
     *
     * @var array
     */
    private const ENGINES = [
        ['Aport', 'Aport robot'],
        ['Google', 'Google'],
        ['msnbot', 'MSN'],
        ['Rambler', 'Rambler'],
        ['Yahoo', 'Yahoo'],
        ['AbachoBOT', 'AbachoBOT'],
        ['accoona', 'Accoona'],
        ['AcoiRobot', 'AcoiRobot'],
        ['ASPSeek', 'ASPSeek'],
        ['CrocCrawler', 'CrocCrawler'],
        ['Dumbot', 'Dumbot'],
        ['FAST-WebCrawler', 'FAST-WebCrawler'],
        ['GeonaBot', 'GeonaBot'],
        ['Gigabot', 'Gigabot'],
        ['Lycos', 'Lycos spider'],
        ['MSRBOT', 'MSRBOT'],
        ['Scooter', 'Altavista robot'],
        ['AltaVista', 'Altavista robot'],
        ['WebAlta', 'WebAlta'],
        ['IDBot', 'ID-Search Bot'],
        ['eStyle', 'eStyle Bot'],
        ['Mail.Ru', 'Mail.Ru Bot'],
        ['Scrubby', 'Scrubby robot'],
        ['Yandex', 'Yandex'],
        ['YaDirectBot', 'Yandex Direct'],
        ['Bot', 'Unknow bot']
    ];

    /**
     * Определяет являться ли данный пользователь ботом поисковой системы.
     *
     * @return bool Вернет true если это бот поисковой системы.
     */
    public static function is(): bool
    {
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

            foreach (self::ENGINES as $engine) {
                if (strstr($userAgent, strtolower($engine[0]))) {
                    return true;
                }
            }
        }

        return false;
    }
}
