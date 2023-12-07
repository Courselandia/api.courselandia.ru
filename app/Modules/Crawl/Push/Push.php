<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Push;

use DB;
use App\Modules\Crawl\Contracts\Pusher;
use App\Modules\Crawl\Push\Pushers\GooglePusher;
use App\Modules\Crawl\Push\Pushers\YandexPusher;
use App\Modules\Page\Models\Page;

/**
 * Отправка URL сайта на индексацию.
 */
class Push
{
    /**
     * Отправители на индексацию.
     *
     * @var Pusher[]
     */
    private array $pushers;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addPusher(new YandexPusher())
            ->addPusher(new GooglePusher());
    }

    /**
     * Запуск процесса отправки на индексацию.
     *
     * @return void
     */
    public function run(): void
    {
        $pushers = $this->getPushers();

        foreach ($pushers as $pusher) {
            $pages = Page::with('crawl')
                ->where(function ($query) use ($pusher) {
                    $query->where('pages.lastmod', '>', DB::raw('crawls.crawled_at'))
                        ->orWhereNull('crawls.pushed_at');
                })
                ->where('engine', $pusher->getEngine()->value)
                ->orderBy('lastmod', 'DESC')
                ->limit($pusher->getLimit())
                ->get();
        }
    }

    /**
     * Получить всех отправителей на индексацию.
     *
     * @return Pusher[] массив отправителей на индексацию.
     */
    public function getPushers(): array
    {
        return $this->pushers;
    }

    /**
     * Добавление отправителя на индексацию.
     *
     * @param Pusher $pusher Отправитель.
     * @return self
     */
    public function addPusher(Pusher $pusher): self
    {
        $this->pushers[] = $pusher;

        return $this;
    }

    /**
     * Удалить всех отправителей на индексацию.
     *
     * @return self
     */
    public function clearPusher(): self
    {
        $this->pushers = [];

        return $this;
    }
}
