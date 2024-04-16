<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Check\Checkers;

use App\Models\Exceptions\InvalidCodeException;
use App\Models\Exceptions\ProcessingException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Crawl\Contracts\Checker;
use App\Modules\Crawl\Engines\Services\YandexService;
use App\Modules\Crawl\Enums\Engine;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Проверка URL сайта на индексацию в Яндекс.
 */
class YandexChecker implements Checker
{
    /**
     * Получение поисковой системы.
     *
     * @return Engine Поисковая система.
     */
    public function getEngine(): Engine
    {
        return Engine::YANDEX;
    }

    /**
     * Получить статус индексации URL.
     *
     * @param string $taskId ID задачи на индексацию.
     * @return bool Вернет true если URL проиндексрован.
     * @throws InvalidCodeException|ProcessingException|ResponseException|GuzzleException
     */
    public function check(string $taskId): bool
    {
        $service = new YandexService();

        return $service->isPushed($taskId);
    }
}
