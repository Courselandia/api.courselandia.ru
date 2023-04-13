<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile\Sources;

use App\Modules\Course\DbFile\Jobs\JobSchool;
use App\Modules\School\Models\School;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\DbFile\Source;
use Illuminate\Database\Eloquent\Builder;

/**
 * Источник для формирования школ.
 */
class SourceSchool extends Source
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
                JobSchool::dispatch('/schools', $result['id'], $result['link'])
                    ->delay(now()->addMinutes(5));

                $this->fireEvent('export');
            }
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return School::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value);
        })
        ->where('status', true)
        ->orderBy('id');
    }
}
