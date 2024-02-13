<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use App\Modules\Tool\Models\Tool;
use Cache;

/**
 * Класс действия для обновления статуса инструментов.
 */
class ToolUpdateStatusAction extends Action
{
    /**
     * ID инструмента.
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
     * @param int|string $id ID инструмента.
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
     * @return ToolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): ToolEntity
    {
        $action = new ToolGetAction($this->id);
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
