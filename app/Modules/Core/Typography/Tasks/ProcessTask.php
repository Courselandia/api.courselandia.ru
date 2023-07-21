<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Query\Builder;
use App\Modules\Process\Models\Process;

/**
 * Типографирование как проходит обучение.
 */
class ProcessTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $processes = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($processes as $process) {
            $process->name = Typography::process($process->name, true);
            $process->text = Typography::process($process->text);

            $process->save();

            $this->fireEvent('finished', [$process]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Process::orderBy('id', 'ASC');
    }
}
