<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Check;

use Carbon\Carbon;
use App\Models\Event;
use App\Modules\Crawl\Jobs\CheckJob;
use App\Modules\Crawl\Contracts\Checker;
use App\Modules\Crawl\Check\Checkers\GoogleChecker;
use App\Modules\Crawl\Check\Checkers\YandexChecker;
use App\Modules\Crawl\Models\Crawl;
use App\Modules\Crawl\Entities\Crawl as CrawlEntity;
use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelData\CursorPaginatedDataCollection;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

/**
 * Проверка статуса индексации URL сайта.
 */
class Check
{
    use Event;

    /**
     * Количество секунд на которые нужно отложить проверку.
     *
     * @var int
     */
    const DELAY = 3;

    /**
     * Проверятели индексации.
     *
     * @var Checker[]
     */
    private array $checkers;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addChecker(new YandexChecker())
            ->addChecker(new GoogleChecker());
    }

    /**
     * Получить общее количество страниц, которые нужно проверить на индексацию.
     *
     * @return int Общее количество страницы.
     */
    public function total(): int
    {
        $checkers = $this->getCheckers();
        $count = 0;

        foreach ($checkers as $checker) {
            $count += $this->getQuery($checker)->count();
        }

        return $count;
    }

    /**
     * Запуск процесса отправки на индексацию.
     *
     * @return void
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
     */
    private function start(): void
    {
        $checkers = $this->getCheckers();

        foreach ($checkers as $checker) {
            $crawls = $this->getCrawls($checker);
            $this->check($checker, $crawls);
        }
    }

    /**
     * Получение запроса для выборки записей об индексации.
     *
     * @param Checker $checker Получатель на индексацию.
     * @return Builder
     */
    private function getQuery(Checker $checker): Builder
    {
        return Crawl::where('crawls.engine', $checker->getEngine()->value)
            ->whereHas('page')
            ->whereNull('crawled_at')
            ->whereNotNull('task_id')
            ->orderBy('pushed_at', 'ASC');
    }

    /**
     * Получение записей на индексацию.
     *
     * @param Checker $checker Получатель на индексацию.
     * @return DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection Вернет коллекцию сущностей индексации.
     */
    private function getCrawls(Checker $checker):  DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection
    {
        $crawls = $this->getQuery($checker)->get();

        return CrawlEntity::collect($crawls->toArray());
    }

    /**
     * Отправка на проверку страниц.
     *
     * @param Checker $checker Проверятель на индексацию.
     * @param DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection $crawls Коллекция сущностей индексации.
     * @return void
     */
    private function check(Checker $checker, DataCollection|CursorPaginatedDataCollection|PaginatedDataCollection $crawls): void
    {
        $delay = Carbon::now();

        foreach ($crawls as $crawl) {
            $delay->addSeconds(self::DELAY);
            CheckJob::dispatch($checker, $crawl)->delay($delay);
            $this->fireEvent('checked');
        }
    }

    /**
     * Получить всех проверятелей индексации на индексацию.
     *
     * @return Checker[] Массив проверятелей индексации.
     */
    public function getCheckers(): array
    {
        return $this->checkers;
    }

    /**
     * Добавление проверятеля индексации.
     *
     * @param Checker $checker Проверятель.
     * @return self
     */
    public function addChecker(Checker $checker): self
    {
        $this->checkers[] = $checker;

        return $this;
    }

    /**
     * Удалить всех проверятелей индексации.
     *
     * @return self
     */
    public function clearCheckers(): self
    {
        $this->checkers = [];

        return $this;
    }
}
