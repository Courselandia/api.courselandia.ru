<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Push\Pushers;

use App\Modules\Crawl\Contracts\Pusher;
use App\Modules\Crawl\Engines\Services\GoogleService;
use App\Modules\Crawl\Enums\Engine;
use Google\Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Отправка URL сайта на индексацию в Google.
 */
class GooglePusher implements Pusher
{
    /**
     * Получение поисковой системы данного отправителя.
     *
     * @return Engine Поисковая система.
     */
    public function getEngine(): Engine
    {
        return Engine::GOOGLE;
    }

    /**
     * Получение лимита на переобход.
     *
     * @return int Вернет остаток квоты на переобход.
     */
    public function getLimit(): int
    {
        $service = new GoogleService();

        return $service->getLimit();
    }

    /**
     * Отправка URL на индексацию.
     *
     * @param string $url URL для индексации.
     * @return string Вернет ID задачи.
     * @throws Exception|GuzzleException
     */
    public function push(string $url): string
    {
        $service = new GoogleService();

        return $service->push($url);
    }
}
