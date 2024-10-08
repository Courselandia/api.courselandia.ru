<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Engine\Providers;

use App\Modules\Crawl\Engines\Providers\GoogleProvider;
use Google\Exception;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

/**
 * Тестирование: Класс провайдер для работы с Google Console.
 */
class GoogleProviderTest extends TestCase
{
    /**
     * Тестирование получение лимитов на переобход.
     *
     * @return void
     */
    public function testGetLimit(): void
    {
        $provider = new GoogleProvider();
        $limit = $provider->getLimit();

        $this->assertIsInt($limit);
    }

    /**
     * Тестирование отправки URL на переобход.
     *
     * @return void
     * @throws GuzzleException|Exception
     */
    public function testPush(): void
    {
        $provider = new GoogleProvider();
        $idTask = $provider->push('https://courselandia.ru');

        $this->assertIsString($idTask);
    }
}
