<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Engine\Services;

use App\Models\Exceptions\InvalidCodeException;
use App\Models\Exceptions\LimitException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\ProcessingException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Crawl\Engines\Services\YandexService;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

/**
 * Тестирование: Класс для работы с Yandex Console.
 */
class YandexServiceTest extends TestCase
{
    /**
     * Тестирование получение лимитов на переобход.
     *
     * @return void
     * @throws ResponseException|GuzzleException|InvalidCodeException
     */
    public function testGetLimit(): void
    {
        $service = new YandexService();
        $limit = $service->getLimit();

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
        $service = new YandexService();
        $idTask = $service->push('https://courselandia.ru');

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
        $service = new YandexService();
        $idTask = $service->push('https://courselandia.ru');

        $this->assertIsString($idTask);

        $status = $service->isPushed($idTask);

        $this->assertIsBool($status);
    }
}
