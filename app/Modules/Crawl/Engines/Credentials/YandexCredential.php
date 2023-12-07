<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Engines\Credentials;

use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Exceptions\InvalidCodeException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Crawl\Engines\Tokens\YandexToken;

/**
 * Получение полномочий для работы с API поисковой системы Yandex.
 */
class YandexCredential
{
    const URL = 'https://api.webmaster.yandex.net';

    /**
     * Получение полномочий.
     *
     * @return YandexToken Токен для Yandex.
     *
     * @throws GuzzleException
     * @throws ResponseException
     * @throws InvalidCodeException
     */
    public function get(): YandexToken
    {
        return new YandexToken($this->receiveUserId(), $this->receiveHost());
    }

    /**
     * Получение ID пользователя.
     *
     * @return int
     * @throws InvalidCodeException
     * @throws ResponseException|GuzzleException
     */
    private function receiveUserId(): int
    {
        $client = new Client();

        try {
            $response = $client->get(self::URL . '/v4/user', $this->getHeader());
            $body = $response->getBody();
            $content = json_decode($body, true);

            return $content['user_id'];
        } catch (ClientException $error) {
            $data = json_decode($error->getResponse()->getBody()->getContents(), true);

            if ($data['error_code'] === 'INVALID_OAUTH_TOKEN') {
                throw new InvalidCodeException(trans('crawl::engines.credentials.yandexCredential.tokenInvalid'));
            }

            throw new ResponseException($error->getMessage());
        }
    }

    /**
     * Метка сайта.
     *
     * @return string Метка.
     */
    public function receiveHost(): string
    {
        return Config::get('crawl.yandex_webmaster_host');
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
                'Authorization' => 'OAuth ' . Config::get('crawl.yandex_token'),
            ],
        ];
    }
}
