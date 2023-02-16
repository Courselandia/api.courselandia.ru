<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Events\Listeners;

use App\Modules\Process\Models\Process;

/**
 * Класс обработчик событий для модели объяснения как проходит обучение.
 */
class ProcessListener
{
    /**
     * Обработчик события при удалении записи.
     *
     * @param  Process  $process  Модель для таблицы объяснения как проходит обучение.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(Process $process): bool
    {
        $process->courses()->detach();

        return true;
    }
}
