<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Engines\Providers;

use App\Models\Exceptions\ParameterInvalidException;
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
        return 30;
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

    /**
     * Проверка, что переобход произошел.
     *
     * @param string $taskId ID задачи на переобход.
     *
     * @return bool Вернет true если переобход произошел.
     * @throws Exception|GuzzleException
     * @throws ParameterInvalidException
     */
    public function isPushed(string $taskId): bool
    {
        $client = new Client();
        $client->setApplicationName(Config::get('crawl.google.application_name'));
        $client->setAuthConfig(storage_path(Config::get('crawl.google.service_account_credentials_json')));
        $client->setScopes(Config::get('crawl.google.scopes'));

        $httpClient = $client->authorize();
        $response = $httpClient->get('https://indexing.googleapis.com/v3/urlNotifications/metadata?url=' . urlencode($taskId));
        $body = $response->getBody();
        $content = json_decode($body, true);

        if (isset($content['error']['code']) && $content['error']['code'] === 404) {
            throw new ParameterInvalidException(trans('crawl::engines.providers.googleProvider.taskNotExist'));
        }

        try {
            return $content['latestUpdate']['type'] === 'URL_UPDATED';
        } catch (\Throwable $error) {
            \Log::error(print_r($content, true));
            throw $error;
        }
    }
}
