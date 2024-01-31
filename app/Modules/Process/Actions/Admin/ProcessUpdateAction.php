<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Process\Data\ProcessUpdate;
use App\Modules\Process\Entities\Process as ProcessEntity;
use App\Modules\Process\Models\Process;
use Cache;

/**
 * Класс действия для обновления объяснения как проходит обучение.
 */
class ProcessUpdateAction extends Action
{
    /**
     * Данные для обновления объяснения как проходит обучение.
     *
     * @var ProcessUpdate
     */
    private ProcessUpdate $data;

    /**
     * @param ProcessUpdate $data Данные для обновления объяснения как проходит обучение.
     */
    public function __construct(ProcessUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProcessEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): ProcessEntity
    {
        $action = new ProcessGetAction($this->data->id);
        $processEntity = $action->run();

        if ($processEntity) {
            $processEntity = ProcessEntity::from([
                ...$processEntity->toArray(),
                ...$this->data->toArray(),
            ]);

            Process::find($this->data->id)->update($processEntity->toArray());
            Cache::tags(['catalog', 'process'])->flush();

            $action = new ProcessGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('process::actions.admin.processUpdateAction.notExistProcess')
        );
    }
}
