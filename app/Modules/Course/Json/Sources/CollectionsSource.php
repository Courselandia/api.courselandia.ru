<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Sources;

use App\Modules\Course\Json\Source;
use App\Modules\Direction\Models\Direction;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Course\Json\Jobs\CollectionsItemLinkJob;

/**
 * Источник для формирования списка коллекций.
 */
class CollectionsSource extends Source
{
    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    public function count(): int
    {
        return $this->getQuery()->count() + 1;
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
                CollectionsItemLinkJob::dispatch('/json/collections/' . $result['link'] . '.json', $result['id'])
                    ->delay(now()->addMinute());

                $this->fireEvent('export');
            }
        }

        CollectionsItemLinkJob::dispatch('/json/collections/all.json')
            ->delay(now()->addMinute());

        $this->fireEvent('export');
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Direction::active()
            ->orderBy('id', 'ASC');
    }
}
