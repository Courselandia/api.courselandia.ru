<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Push\Pushers;

use App\Models\Exceptions\ResponseException;
use App\Modules\Crawl\Contracts\Pusher;
use App\Modules\Crawl\Engines\Services\YandexService;
use App\Modules\Crawl\Enums\Engine;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Отправка URL сайта на индексацию в Яндекс.
 */
class YandexPusher implements Pusher
{
    /**
     * Получение поисковой системы данного отправителя.
     *
     * @return Engine Поисковая система.
     */
    public function getEngine(): Engine
    {
        return Engine::YANDEX;
    }

    /**
     * Получение лимита на переобход.
     *
     * @return int Вернет остаток квоты на переобход.
     * @throws ResponseException|GuzzleException
     */
    public function getLimit(): int
    {
        $service = new YandexService();

        return $service->getLimit();
    }
}
