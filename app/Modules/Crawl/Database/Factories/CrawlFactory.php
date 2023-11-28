<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Database\Factories;

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
            'path' => $this->faker->filePath(),
            'lastmod' => Carbon::now(),
        ];
    }
}
