<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Check\Checkers;

use App\Modules\Crawl\Contracts\Checker;
use App\Modules\Crawl\Engines\Services\GoogleService;
use App\Modules\Crawl\Enums\Engine;
use Google\Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Проверка URL сайта на индексацию в Google.
 */
class GoogleChecker implements Checker
{
    /**
     * Получение поисковой системы.
     *
     * @return Engine Поисковая система.
     */
    public function getEngine(): Engine
    {
        return Engine::GOOGLE;
    }

    /**
     * Получить статус индексации URL.
     *
     * @param string $taskId ID задачи на индексацию.
     * @return bool Вернет true если URL проиндексрован.
     * @throws Exception|GuzzleException
     */
    public function check(string $taskId): bool
    {
        $service = new GoogleService();

        return $service->isPushed($taskId);
    }
}
