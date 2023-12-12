<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Contracts;

use App\Modules\Crawl\Enums\Engine;

/**
 * Интерфейс проверки статуса индексации.
 */
interface Checker
{
    /**
     * Получение поисковой системы.
     *
     * @return Engine Поисковая система.
     */
    public function getEngine(): Engine;

    /**
     * Получить статус индексации URL.
     *
     * @param string $taskId ID задачи на индексацию.
     * @return bool Вернет true если URL проиндексрован.
     */
    public function check(string $taskId): bool;
}
