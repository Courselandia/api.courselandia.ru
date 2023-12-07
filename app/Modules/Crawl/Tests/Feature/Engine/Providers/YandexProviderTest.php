<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Engine\Providers;

use App\Models\Exceptions\InvalidCodeException;
use App\Models\Exceptions\LimitException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\ProcessingException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Crawl\Engines\Credentials\YandexCredential;
use App\Modules\Crawl\Engines\Providers\YandexProvider;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

/**
 * Тестирование: Класс провайдер для работы с Yandex Console.
 */
class YandexProviderTest extends TestCase
{
    /**
     * Тестирование получение лимитов на переобход.
     *
     * @return void
     * @throws ResponseException|GuzzleException|InvalidCodeException
     */
    public function testGetLimit(): void
    {
        $credential = new YandexCredential();
        $provider = new YandexProvider($credential->get());
        $limit = $provider->getLimit();

        $this->assertIsInt($limit);
    }

    /**
     * Тестирование отправки URL на переобход.
     *
     * @return void
     * @throws LimitException|ParameterInvalidException|ResponseException|GuzzleException|InvalidCodeException
     */
    public function testPush(): void
    {
        $credential = new YandexCredential();
        $provider = new YandexProvider($credential->get());
        $idTask = $provider->push('https://courselandia.ru');

        $this->assertIsString($idTask);
    }

    /**
     * Тестирование работы метода проверки, что переобход призошел.
     *
     * @return void
     * @throws LimitException|ParameterInvalidException|ResponseException|GuzzleException|InvalidCodeException
     * @throws ProcessingException
     */
    public function testIsPushed(): void
    {
        $credential = new YandexCredential();
        $provider = new YandexProvider($credential->get());
        $idTask = $provider->push('https://courselandia.ru');

        $this->assertIsString($idTask);

        $status = $provider->isPushed($idTask);

        $this->assertIsBool($status);
    }
}
