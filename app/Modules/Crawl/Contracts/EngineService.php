<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Contracts;

/**
 * Интерфейс для работы с поисковой системой.
 */
interface EngineService
{
    /**
     * Получение лимита возможности отправить на индексацию.
     *
     * @return int Вернет остаток квоты на переобход.
     */
    public function getLimit(): int;

    /**
     * Отправка URL на индексацию.
     *
     * @param string $url URL для индексации.
     * @return string Вернет ID задачи.
     */
    public function push(string $url): string;

    /**
     * Вернет статус индексации.
     *
     * @param string $taskId ID задачи.
     * @return bool Вернет true если индексация прошла.
     */
    public function isPushed(string $taskId): bool;
}
