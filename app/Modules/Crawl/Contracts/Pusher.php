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
 * Интерфейс отправителя на индексацию.
 */
interface Pusher
{
    /**
     * Получение поисковой системы данного отправителя.
     *
     * @return Engine Поисковая система.
     */
    public function getEngine(): Engine;

    /**
     * Получение лимита на переобход.
     *
     * @return int Вернет остаток квоты на переобход.
     */
    public function getLimit(): int;
}
