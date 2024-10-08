<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Engines\Services;

use Google\Exception;
use GuzzleHttp\Exception\GuzzleException;
use App\Modules\Crawl\Contracts\EngineService;
use App\Modules\Crawl\Engines\Providers\GoogleProvider;

/**
 * Работа с поисковой системой Google.
 */
class GoogleService implements EngineService
{
    /**
     * Получение лимита возможности отправить на индексацию.
     *
     * @return int Вернет остаток квоты на переобход.
     */
    public function getLimit(): int
    {
        $provider = new GoogleProvider();

        return $provider->getLimit();
    }

    /**
     * Отправка URL на индексацию.
     *
     * @param string $url URL для индексации.
     * @return string Вернет ID задачи.
     * @throws GuzzleException|Exception
     */
    public function push(string $url): string
    {
        $provider = new GoogleProvider();

        return $provider->push($url);
    }
}
