<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Actions\Admin;

use App\Models\Action;
use App\Modules\Process\Models\Process;
use Cache;

/**
 * Класс действия для удаления объяснения как проходит обучение.
 */
class ProcessDestroyAction extends Action
{
    /**
     * Массив ID записей.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID записей.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Process::destroy($this->ids);
            Cache::tags(['catalog', 'process'])->flush();
        }

        return true;
    }
}
