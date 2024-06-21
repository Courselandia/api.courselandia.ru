<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Sources;

use App\Modules\Course\Json\Jobs\TeachersItemJob;
use App\Modules\Course\Json\Source;

/**
 * Источник для формирования всех учителей.
 */
class TeachersSource extends Source
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
        TeachersItemJob::dispatch('/json/teachers.json')
            ->delay(now()->addMinute());
    }
}
