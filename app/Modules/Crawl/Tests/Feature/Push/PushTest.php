<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Push;

use Carbon\Carbon;
use Tests\TestCase;
use App\Modules\Crawl\Enums\Engine;
use App\Modules\Crawl\Models\Crawl;
use App\Modules\Crawl\Plan\Plan;
use App\Modules\Crawl\Push\Push;
use App\Modules\Page\Models\Page;
use App\Modules\Crawl\Push\Pushers\FakePusher;

/**
 * Тестирование отправки URL сайта на индексацию.
 */
class PushTest extends TestCase
{
    /**
     * Тестирование запуска.
     *
     * @return void
     */
    public function testRun(): void
    {
        Page::truncate();
        Crawl::truncate();

        Page::factory()->create([
            'path' => '/test-1',
            'lastmod' => Carbon::now()->addMonths(-4),
        ]);

        Page::factory()->create([
            'path' => '/test-2',
            'lastmod' => Carbon::now()->addMonths(-3),
        ]);

        Page::factory()->create([
            'path' => '/test-3',
            'lastmod' => Carbon::now()->addMonths(-5),
        ]);

        $plan = new Plan();
        $plan->clearEngines()
            ->addEngine(Engine::FAKE);
        $plan->start();

        $push = new Push();
        $push->clearPushers()
            ->addPusher(new FakePusher());
        $total = $push->total();
        $push->run();

        $this->assertIsNumeric($total);
        $this->assertEquals(3, $total);
    }
}
