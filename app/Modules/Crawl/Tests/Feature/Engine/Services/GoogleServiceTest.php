<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Engine\Services;

use App\Modules\Crawl\Engines\Services\GoogleService;
use Google\Exception;
use GuzzleHttp\Exception\GuzzleException;
use Tests\TestCase;

/**
 * Тестирование: Класс для работы с Google Console.
 */
class GoogleServiceTest extends TestCase
{
    /**
     * Тестирование получение лимитов на переобход.
     *
     * @return void
     */
    public function testGetLimit(): void
    {
        $service = new GoogleService();
        $limit = $service->getLimit();

        $this->assertIsInt($limit);
    }

    /**
     * Тестирование отправки URL на переобход.
     *
     * @return void
     * @throws Exception|GuzzleException
     */
    public function testPush(): void
    {
        $service = new GoogleService();
        $idTask = $service->push('https://courselandia.ru');

        $this->assertIsString($idTask);
    }
}
