<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Engines\Providers;

use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Crawl\Engines\Tokens\YandexToken;
use App\Models\Exceptions\LimitException;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Провайдер для работы с Yandex Console.
 */
class YandexProvider
{
    const URL = 'https://api.webmaster.yandex.net';

    /**
     * Токен Yandex.
     *
     * @var YandexToken
     */
    private YandexToken $token;

    /**
     * Конструктор.
     */
    public function __construct(YandexToken $token)
    {
        $this->token = $token;
    }

    /**
     * Получение лимита на переобход.
     *
     * @return int Вернет остаток квоты на переобход.
     * @throws ResponseException|GuzzleException
     */
    public function getLimit(): int
    {
        try {
            $client = new Client();

            $fullUrl = self::URL . '/v4/user/' . $this->token->getUserId() . '/hosts/' . $this->token->getHost() . '/recrawl/quota';
            $response = $client->get($fullUrl, $this->getHeader());
            $body = $response->getBody();
            $content = json_decode($body, true);

            return $content['quota_remainder'];
        } catch (ClientException $error) {
            throw new ResponseException($error->getMessage());
        }
    }

    /**
     * Отправка URL на переобход.
     *
     * @param string $url URL для переобхода.
     *
     * @return string ID задачи.
     * @throws ResponseException|LimitException|ParameterInvalidException|GuzzleException
     */
    public function push(string $url): string
    {
        $client = new Client();

        try {
            $fullUrl = self::URL . '/v4/user/' . $this->token->getUserId() . '/hosts/' . $this->token->getHost() . '/recrawl/queue';
            $response = $client->post($fullUrl, [
                ...$this->getHeader(),
                'json' => [
                    'url' => $url,
                ],
            ]);
            $body = $response->getBody();
            $content = json_decode($body, true);

            return $content['task_id'];
        } catch (ClientException $error) {
            $data = json_decode($error->getResponse()->getBody()->getContents(), true);

            if ($data['error_code'] === 'URL_ALREADY_ADDED') {
                throw new ParameterInvalidException(trans('crawl::engines.providers.yandexProvider.urlAlreadyAdded'));
            }

            if ($data['error_code'] === 'QUOTA_EXCEEDED') {
                throw new LimitException(trans('crawl::engines.providers.yandexProvider.quotaExceeded'));
            }

            throw new ResponseException($error->getMessage());
        }
    }

    /**
     * Получение заголовка.
     *
     * @return array[]
     */
    private function getHeader(): array
    {
        return [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'OAuth ' . Config::get('crawl.yandex.token'),
            ],
        ];
    }
}
