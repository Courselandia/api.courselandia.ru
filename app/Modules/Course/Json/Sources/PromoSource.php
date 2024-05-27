<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Sources;

use App\Modules\Course\Json\Jobs\PromoItemLinkJob;
use App\Modules\Course\Json\Source;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;
use Storage;

/**
 * Источник для формирования промоматериалов.
 */
class PromoSource extends Source
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
                PromoItemLinkJob::dispatch('/json/promos/' . $result['link'] . '.json', $result['id'], $result['link'])
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

        $schools = School::whereNotIn('id', $activeIds)
            ->get()
            ?->toArray();

        $paths = [];

        foreach ($schools as $school) {
            $path = '/json/promos/' . $school['link'] . '.json';

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
        return School::active()
            ->with([
                'promocodes' => function ($query) {
                    $query->applicable();
                },
                'promotions' => function ($query) {
                    $query->applicable();
                },
            ])
            ->where(function ($query) {
                $query->whereHas('promocodes', function ($query) {
                    $query->applicable();
                })
                ->orWhereHas('promotions', function ($query) {
                    $query->applicable();
                });
            })
            ->orderBy('id', 'ASC');
    }
}
