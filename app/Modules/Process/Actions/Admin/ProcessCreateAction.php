<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Process\Entities\Process as ProcessEntity;
use App\Modules\Process\Models\Process;
use Cache;

/**
 * Класс действия для создания объяснения как проходит обучение.
 */
class ProcessCreateAction extends Action
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return ProcessEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ProcessEntity
    {
        $processEntity = new ProcessEntity();
        $processEntity->name = $this->name;
        $processEntity->text = $this->text;
        $processEntity->status = $this->status;

        $process = Process::create($processEntity->toArray());
        Cache::tags(['catalog', 'process'])->flush();

        $action = app(ProcessGetAction::class);
        $action->id = $process->id;

        return $action->run();
    }
}
