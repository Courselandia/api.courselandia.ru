<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Database\Factories;

use App\Modules\Crawl\Enums\Engine;
use Carbon\Carbon;
use App\Modules\Crawl\Models\Crawl;
use App\Modules\Page\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Фабрика модели индексации.
 */
class CrawlFactory extends Factory
{
    /**
     * Модель фабрики.
     *
     * @var string
     */
    protected $model = Crawl::class;

    /**
     * Определение модели.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'page_id' => Page::factory(),
            'task_id' => $this->faker->name(),
            'pushed_at' => Carbon::now(),
            'crawled_at' => Carbon::now(),
            'engine' => Engine::YANDEX->value,
        ];
    }
}
