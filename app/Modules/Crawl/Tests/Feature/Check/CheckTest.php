<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Check;

use App\Modules\Crawl\Check\Check;
use App\Modules\Crawl\Models\Crawl;
use App\Modules\Crawl\Check\Checkers\FakeChecker;
use App\Modules\Page\Models\Page;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Тестирование проверки индексации страниц сайта.
 */
class CheckTest extends TestCase
{
    /**
     * Тестирование запуска.
     *
     * @return void
     */
    public function testRun(): void
    {
        $page = Page::factory()->create([
            'path' => '/',
            'lastmod' => Carbon::now()->addMonths(-5),
        ]);

        Crawl::factory()->create([
            'crawled_at' => null,
            'page_id' => $page->id,
        ]);

        $page = Page::factory()->create([
            'path' => '/test-1',
            'lastmod' => Carbon::now()->addMonths(-4),
        ]);

        Crawl::factory()->create([
            'crawled_at' => null,
            'page_id' => $page->id,
        ]);

        $page = Page::factory()->create([
            'path' => '/test-2',
            'lastmod' => Carbon::now()->addMonths(-3),
        ]);

        Crawl::factory()->create([
            'crawled_at' => null,
            'page_id' => $page->id,
        ]);

        $check = new Check();
        $check->clearCheckers()
            ->addChecker(new FakeChecker());
        $total = $check->total();
        $check->run();

        $this->assertIsNumeric($total);
        $this->assertEquals(3, $total);
    }
}
