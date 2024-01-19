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
use App\Modules\Process\Entities\Process as ProcessEntity;
use App\Modules\Process\Models\Process;
use Cache;

/**
 * Класс действия для обновления статуса объяснения как проходит обучение.
 */
class ProcessUpdateStatusAction extends Action
{
    /**
     * ID объяснения как проходит обучение.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Статус.
     *
     * @var bool
     */
    private bool $status;

    /**
     * @param int|string $id ID объяснения как проходит обучение.
     * @param bool $status Статус.
     */
    public function __construct(int|string $id, bool $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProcessEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): ProcessEntity
    {
        $action = new ProcessGetAction($this->id);
        $processEntity = $action->run();

        if ($processEntity) {
            $processEntity->status = $this->status;
            Process::find($this->id)->update($processEntity->toArray());
            Cache::tags(['catalog', 'process'])->flush();

            return $processEntity;
        }

        throw new RecordNotExistException(
            trans('process::actions.admin.processUpdateStatusAction.notExistProcess')
        );
    }
}
