<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Sources;

use App\Modules\Course\Json\Jobs\RatedCoursesItemLinkJob;
use App\Modules\Course\Json\Source;

/**
 * Источник для всех лучших курсов.
 */
class RatedCoursesSource extends Source
{
    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * Запуск экспорта данных.
     *
     * @return void.
     */
    public function export(): void
    {
        RatedCoursesItemLinkJob::dispatch('/json/courses/rated.json')
            ->delay(now()->addMinute());

        $this->fireEvent('export');
    }
}
