<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Push\Pushers;

use App\Modules\Crawl\Contracts\Pusher;
use App\Modules\Crawl\Enums\Engine;

/**
 * Отправка URL сайта на индексацию для тестирования (фейковый).
 */
class FakePusher implements Pusher
{
    /**
     * Получение поисковой системы данного отправителя.
     *
     * @return Engine Поисковая система.
     */
    public function getEngine(): Engine
    {
        return Engine::FAKE;
    }

    /**
     * Получение лимита на переобход.
     *
     * @return int Вернет остаток квоты на переобход.
     */
    public function getLimit(): int
    {
        return 100;
    }

    /**
     * Отправка URL на индексацию.
     *
     * @param string $url URL для индексации.
     * @return string Вернет ID задачи.
     */
    public function push(string $url): string
    {
        return (string)rand(100000, 10000000);
    }
}
