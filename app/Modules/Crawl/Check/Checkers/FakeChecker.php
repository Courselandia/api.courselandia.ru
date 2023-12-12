<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Check\Checkers;

use App\Modules\Crawl\Contracts\Checker;
use App\Modules\Crawl\Enums\Engine;

/**
 * Проверка URL сайта на индексацию для тестирования (фейковый).
 */
class FakeChecker implements Checker
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
     */
    public function check(string $taskId): bool
    {
        return true;
    }
}
