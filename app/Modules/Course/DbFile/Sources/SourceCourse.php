<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile\Sources;

use App\Modules\Course\DbFile\Jobs\JobTool;
use App\Modules\Course\DbFile\Source;

/**
 * Источник для формирования курсов.
 */
class SourceCourse extends Source
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
        JobTool::dispatch('/course')
            ->delay(now()->addMinutes(5));

        $this->fireEvent('export');
    }
}
