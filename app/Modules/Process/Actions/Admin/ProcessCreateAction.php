<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Actions\Admin;

use App\Models\Action;
use App\Modules\Process\Data\ProcessCreate;
use App\Modules\Process\Entities\Process as ProcessEntity;
use App\Modules\Process\Models\Process;
use Cache;

/**
 * Класс действия для создания объяснения как проходит обучение.
 */
class ProcessCreateAction extends Action
{
    /**
     * Данные для создания объяснения как проходит обучение.
     *
     * @var ProcessCreate
     */
    private ProcessCreate $data;

    /**
     * @param ProcessCreate $data Данные для создания объяснения как проходит обучение.
     */
    public function __construct(ProcessCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProcessEntity Вернет результаты исполнения.
     */
    public function run(): ProcessEntity
    {
        $processEntity = ProcessEntity::from($this->data->toArray());

        $process = Process::create($processEntity->toArray());
        Cache::tags(['catalog', 'process'])->flush();

        $action = new ProcessGetAction($process->id);

        return $action->run();
    }
}
