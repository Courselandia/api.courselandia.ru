<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Jobs;

use App\Models\Exceptions\ParameterInvalidException;
use Log;
use App\Models\Exceptions\LimitException;
use App\Modules\Crawl\Contracts\Pusher;
use Config;
use App\Modules\Crawl\Models\Crawl;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Page\Entities\Page;

/**
 * Задание на отправку на индексацию в поисковую систему.
 */
class PushJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * Отправитель на индексацию.
     *
     * @var Pusher
     */
    private Pusher $pusher;

    /**
     * Сущность страницы, которую нужно отправить на индексацию.
     *
     * @var Page
     */
    private Page $page;

    /**
     * Конструктор.
     *
     * @param Pusher $pusher Отправитель на индексацию.
     * @param Page $page Сущность страницы, которую нужно отправить на индексацию.
     */
    public function __construct(Pusher $pusher, Page $page)
    {
        $this->pusher = $pusher;
        $this->page = $page;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $taskId = $this->pusher->push(Config::get('app.url') . $this->page->path);

            $crawl = Crawl::where('page_id', $this->page->id)
                ->where('engine', $this->pusher->getEngine()->value)
                ->first();

            if (!$crawl) {
                $crawl = new Crawl();
                $crawl->page_id = $this->page->id;
                $crawl->engine = $this->pusher->getEngine()->value;
            }

            $crawl->task_id = $taskId;
            $crawl->pushed_at = Carbon::now();
            $crawl->crawled_at = null;
            $crawl->save();
        } catch (ParameterInvalidException|LimitException $error) {
            Log::warning($error->getMessage() . ' Path: ' . $this->page->path);
        }
    }
}
