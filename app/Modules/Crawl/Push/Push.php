<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Push;

use DB;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Entity;
use App\Modules\Crawl\Jobs\PushJob;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Crawl\Contracts\Pusher;
use App\Modules\Crawl\Push\Pushers\GooglePusher;
use App\Modules\Crawl\Push\Pushers\YandexPusher;
use App\Modules\Page\Models\Page;
use App\Modules\Page\Entities\Page as PageEntity;
use Illuminate\Database\Eloquent\Builder;

/**
 * Отправка URL сайта на индексацию.
 */
class Push
{
    use Event;

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
        $this/*->addPusher(new YandexPusher())*/
            ->addPusher(new GooglePusher());
    }

    /**
     * Получить общее количество страниц отправляемых на индексацию.
     *
     * @return int Общее количество страницы отправляемых на индексацию.
     */
    public function total(): int
    {
        $pushers = $this->getPushers();
        $count = 0;

        foreach ($pushers as $pusher) {
            $count += $this->getQuery($pusher)->get()->count();
        }

        return $count;
    }

    /**
     * Запуск процесса отправки на индексацию.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function run(): void
    {
        $this->offLimits();
        $this->start();
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Запуск действия.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    private function start(): void
    {
        $pushers = $this->getPushers();

        foreach ($pushers as $pusher) {
            $pages = $this->getPages($pusher);
            $this->push($pusher, $pages);
        }
    }

    /**
     * Получение запроса для выборки страниц на индексацию.
     * Получаем отсортированные страницы по последней дате обновления в обратном порядке (самые поздние на первое место) - lastmod ASC.
     * Берем те страницы, которые либо не были проиндексированы этим модулем.
     * Либо берем те страницы, которые были обновлены этим модулем, но дата их индексации больше даты обновления - pages.lastmod > crawls.crawled_at.
     *
     * @param Pusher $pusher Отправитель на индексацию.
     * @return Builder
     */
    private function getQuery(Pusher $pusher): Builder
    {
        return Page::with('crawl')
            ->where(function ($query) use ($pusher) {
                $query->whereDoesntHave('crawl')
                    ->orWhereHas('crawl', function ($query) use ($pusher) {
                        $query->where('pages.lastmod', '>', DB::raw('crawls.crawled_at'))
                            ->where('crawls.engine', $pusher->getEngine()->value);
                    });
            })
            ->orderBy('pages.lastmod', 'ASC')
            ->limit($pusher->getLimit());
    }

    /**
     * Получение страниц сайта, которые нужно отправить на индексацию.
     *
     * @param Pusher $pusher Отправитель на индексацию.
     * @return PageEntity[] Вернет массив сущностей страниц.
     * @throws ParameterInvalidException
     */
    private function getPages(Pusher $pusher): array
    {
        $pages = $this->getQuery($pusher)->get();

        return Entity::toEntities($pages->toArray(), new PageEntity());
    }

    /**
     * Отправка на индексацию страниц.
     *
     * @param Pusher $pusher Отправитель на индексацию.
     * @param PageEntity[] $pages Массив сущностей страниц, которые должны быть отправлены на индексацию.
     * @return void
     */
    private function push(Pusher $pusher, array $pages): void
    {
        $delay = Carbon::now();

        foreach ($pages as $page) {
            $delay->addSeconds(5);
            PushJob::dispatch($pusher, $page)->delay($delay);
            $this->fireEvent('pushed');
        }
    }

    /**
     * Получить всех отправителей на индексацию.
     *
     * @return Pusher[] Массив отправителей на индексацию.
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
    public function clearPushers(): self
    {
        $this->pushers = [];

        return $this;
    }
}
