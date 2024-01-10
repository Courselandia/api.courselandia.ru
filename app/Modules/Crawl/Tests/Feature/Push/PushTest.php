<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Tests\Feature\Push;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Crawl\Push\Push;
use App\Modules\Crawl\Push\Pushers\FakePusher;
use App\Modules\Page\Models\Page;
use Carbon\Carbon;
use Tests\TestCase;

/**
 * Тестирование отправки URL сайта на индексацию.
 */
class PushTest extends TestCase
{
    /**
     * Тестирование запуска.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function testRun(): void
    {
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

        $push = new Push();
        $push->clearPushers()
            ->addPusher(new FakePusher());
        $total = $push->total();
        $push->run();

        $this->assertIsNumeric($total);
        $this->assertEquals(3, $total);
    }
}
