<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Export\Sources;

use App\Modules\Course\Export\Jobs\CourseAllItemJob;
use App\Modules\Course\Export\Jobs\CourseItemJob;
use App\Modules\Course\Export\Source;

/**
 * Источник для формирования курсов.
 */
class CourseAllSource extends Source
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
        CourseAllItemJob::dispatch('courses')
            ->delay(now()->addMinute());

        $this->fireEvent('export');
    }
}
