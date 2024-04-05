<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Sync;

use App\Models\Event;
use App\Modules\Collection\Jobs\SynchronizeJob;
use App\Modules\Collection\Models\Collection;
use Illuminate\Database\Eloquent\Builder;

/**
 * Синхронизация курсов коллекций.
 */
class Synchronize
{
    use Event;

    /**
     * Запуск синхронизации.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->do();
    }

    /**
     * Получить количество коллекций, которые нужно синхронизировать.
     *
     * @return int Количество коллекций.
     */
    public function getTotal(): int
    {
        return $this->getQuery()->count();
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
     * Синхронизация.
     *
     * @return void
     */
    private function do(): void
    {
        $collections = $this->getQuery()->get();

        foreach ($collections as $collection) {
            SynchronizeJob::dispatch($collection->id)
                ->delay(now()->addMinute());

            $this->fireEvent('sync', [$collection]);
        }
    }

    /**
     * @return Builder
     */
    private function getQuery(): Builder
    {
        return Collection::active()
            ->orderBy('id', 'ASC');
    }
}