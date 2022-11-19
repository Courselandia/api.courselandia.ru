<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;
use Cache;
use ReflectionException;

/**
 * Класс действия для обновления статуса инструментов.
 */
class ToolUpdateStatusAction extends Action
{
    /**
     * ID инструмента.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return ToolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): ToolEntity
    {
        $action = app(ToolGetAction::class);
        $action->id = $this->id;
        $toolEntity = $action->run();

        if ($toolEntity) {
            $toolEntity->status = $this->status;
            Tool::find($this->id)->update($toolEntity->toArray());
            Cache::tags(['catalog', 'tool'])->flush();

            return $toolEntity;
        }

        throw new RecordNotExistException(
            trans('tool::actions.admin.toolUpdateStatusAction.notExistTool')
        );
    }
}
