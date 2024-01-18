<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Jobs;

use Log;
use App\Modules\Crawl\Contracts\Checker;
use App\Modules\Crawl\Models\Crawl;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\Exceptions\ParameterInvalidException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Crawl\Entities\Crawl as CrawlEntity;

/**
 * Задание на проверку индексации в поисковой системе.
 */
class CheckJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Dispatchable;

    /**
     * Проверятель индексации.
     *
     * @var Checker
     */
    private Checker $checker;

    /**
     * Сущность индексации.
     *
     * @var CrawlEntity
     */
    private CrawlEntity $crawl;

    /**
     * Конструктор.
     *
     * @param Checker $checker Проверятель индексации.
     * @param CrawlEntity $crawl Сущность индексации.
     */
    public function __construct(Checker $checker, CrawlEntity $crawl)
    {
        $this->checker = $checker;
        $this->crawl = $crawl;
    }

    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        try {
            $status = $this->checker->check($this->crawl->task_id);

            if ($status) {
                $this->crawl->crawled_at = Carbon::now();

                Crawl::find($this->crawl->id)->update($this->crawl->toArray());
            }
        } catch (ParameterInvalidException $error) {
            Log::notice($error->getMessage() . ' Task ID: ' . $this->crawl->task_id);

            $this->crawl->crawled_at = Carbon::now();
            Crawl::find($this->crawl->id)->update($this->crawl->toArray());
        }
    }
}
