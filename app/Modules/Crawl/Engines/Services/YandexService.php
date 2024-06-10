<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Engines\Services;

use App\Models\Exceptions\InvalidCodeException;
use App\Models\Exceptions\LimitException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Crawl\Contracts\EngineService;
use App\Modules\Crawl\Engines\Credentials\YandexCredential;
use App\Modules\Crawl\Engines\Providers\YandexProvider;
use App\Modules\Crawl\Engines\Tokens\YandexToken;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Работа с поисковой системой Yandex.
 */
class YandexService implements EngineService
{
    /**
     * Токен Yandex.
     *
     * @var YandexToken
     */
    private YandexToken $token;

    /**
     * Конструктор.
     *
     * @throws InvalidCodeException|ResponseException|GuzzleException
     */
    public function __construct()
    {
        $credential = new YandexCredential();
        $this->token = $credential->get();
    }

    /**
     * Получение лимита возможности отправить на индексацию.
     *
     * @return int Вернет остаток квоты на переобход.
     * @throws ResponseException|GuzzleException
     */
    public function getLimit(): int
    {
        $provider = new YandexProvider($this->token);

        return $provider->getLimit();
    }

    /**
     * Отправка URL на индексацию.
     *
     * @param string $url URL для индексации.
     * @return string Вернет ID задачи.
     * @throws LimitException|ResponseException|GuzzleException
     * @throws ParameterInvalidException
     */
    public function push(string $url): string
    {
        $provider = new YandexProvider($this->token);

        return $provider->push($url);
    }
}
