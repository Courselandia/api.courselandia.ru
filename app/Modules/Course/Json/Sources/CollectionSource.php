<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Sources;

use Storage;
use App\Modules\Course\Json\Source;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Collection\Models\Collection;
use App\Modules\Course\Json\Jobs\CollectionItemLinkJob;

/**
 * Источник для формирования коллекции.
 */
class CollectionSource extends Source
{
    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск экспорта данных.
     *
     * @return void.
     */
    public function export(): void
    {
        $count = $this->count();

        for ($i = 0; $i <= $count; $i++) {
            $result = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($result) {
                CollectionItemLinkJob::dispatch('/json/collections/link/' . $result['link'] . '.json', $result['id'], $result['link'])
                    ->delay(now()->addMinute());

                $this->fireEvent('export');
            }
        }
    }

    /**
     * Запуск удаления не активных данных.
     *
     * @return void.
     */
    public function delete(): void
    {
        $activeIds = $this->getQuery()
            ->get()
            ->pluck('id');

        $collections = Collection::whereNotIn('id', $activeIds)
            ->get()
            ?->toArray();

        $paths = [];

        foreach ($collections as $collection) {
            $path = '/json/collections/link/' . $collection['link'] . '.json';

            if (Storage::drive('public')->exists($path)) {
                $paths[] = $path;
            }
        }

        Storage::drive('public')->delete($paths);
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Collection::active()
            ->orderBy('name', 'ASC');
    }
}
