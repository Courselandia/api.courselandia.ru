<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Contracts;

/**
 * Абстрактный класс для работы с поисковой системой.
 */
abstract class EngineService
{
    /**
     * Получение лимита возможности отправить на индексацию.
     *
     * @return int Вернет остаток квоты на переобход.
     */
    abstract public function getLimit(): int;

    /**
     * Отправка URL на индексацию.
     *
     * @param string $url URL для индексации.
     * @return string Вернет ID задачи.
     */
    abstract public function push(string $url): string;

    /**
     * Вернет статус индексации.
     *
     * @param string $taskId ID задачи.
     * @return bool Вернет true если индексация прошла.
     */
    abstract public function isPushed(string $taskId): bool;
}
