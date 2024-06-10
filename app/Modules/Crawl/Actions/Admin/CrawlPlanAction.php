<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Actions\Admin;

use App\Modules\Crawl\Plan\Plan;
use App\Models\Action;

/**
 * Класс действия для запуска планирования задач.
 */
class CrawlPlanAction extends Action
{
    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     */
    public function run(): bool
    {
        $plan = new Plan();
        $plan->start();

        return true;
    }
}
