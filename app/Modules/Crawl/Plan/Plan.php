<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Plan;

use Carbon\Carbon;
use App\Models\Event;
use App\Modules\Crawl\Enums\Engine;
use App\Modules\Crawl\Models\Crawl;
use App\Modules\Page\Models\Page;

/**
 * Запланировать индексацию страниц сайта в поисковых системах.
 */
class Plan
{
    use Event;

    /**
     * Поисковые системы.
     *
     * @var Engine[]
     */
    private array $engines;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addEngine(Engine::GOOGLE);
    }

    /**
     * Запуск планирования.
     *
     * @return void
     */
    public function start(): void
    {
        $this->clear();
        $this->create();
    }

    /**
     * Общее количество генерируемых задач.
     *
     * @return int Количество генерируемых задач.
     */
    public function total(): int
    {
        $engines = $this->getEngines();

        return count($engines) * Page::count();
    }

    /**
     * Удаление ранее запланированные задания на индексацию.
     *
     * @return void
     */
    private function clear(): void
    {
        Crawl::whereNotNull('id')->delete();
    }

    /**
     * Создание задач на индексацию.
     *
     * @return void
     */
    private function create(): void
    {
        foreach ($this->getEngines() as $engine) {
            $pages = Page::all();

            foreach ($pages as $page) {
                $crawl = Crawl::create([
                    'page_id' => $page->id,
                    'pushed_at' => Carbon::now(),
                    'engine' => $engine->value,
                ]);

                $this->fireEvent('created', [$crawl]);
            }
        }
    }

    /**
     * Получить все поисковые системы.
     *
     * @return Engine[] Массив поисковых систем, в которые планируем провести отправку на индексацию страниц.
     */
    public function getEngines(): array
    {
        return $this->engines;
    }

    /**
     * Добавление поисковой системы.
     *
     * @param Engine $engine Поисковая система.
     * @return self
     */
    public function addEngine(Engine $engine): self
    {
        $this->engines[] = $engine;

        return $this;
    }

    /**
     * Удалить все поисковые системы.
     *
     * @return self
     */
    public function clearEngines(): self
    {
        $this->engines = [];

        return $this;
    }
}