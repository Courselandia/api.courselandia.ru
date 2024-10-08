<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Engines\Providers;

use Config;
use Google\Client;
use Google\Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Провайдер для работы с Google Search Console.
 */
class GoogleProvider
{
    /**
     * Получение лимита на переобход.
     *
     * @return int Вернет остаток квоты на переобход.
     */
    public function getLimit(): int
    {
        return 2000;
    }

    /**
     * Отправка URL на переобход.
     *
     * @param string $url URL для переобхода.
     *
     * @return string ID задачи.
     * @throws GuzzleException|Exception
     */
    public function push(string $url): string
    {
        $client = new Client();
        $client->setApplicationName(Config::get('crawl.google.application_name'));
        $client->setAuthConfig(storage_path(Config::get('crawl.google.service_account_credentials_json')));
        $client->setScopes(Config::get('crawl.google.scopes'));

        $httpClient = $client->authorize();

        $content = json_encode([
            'url' => $url,
            'type' => 'URL_UPDATED',
        ]);

        $httpClient->post('https://indexing.googleapis.com/v3/urlNotifications:publish', ['body' => $content]);

        return $url;
    }
}
