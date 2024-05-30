<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Widget\Entities\Widget as WidgetEntity;
use App\Modules\Widget\Models\Widget;
use Cache;

/**
 * Класс действия для обновления статуса виджета.
 */
class WidgetUpdateStatusAction extends Action
{
    /**
     * ID виджета.
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
     * @param int|string $id ID виджета.
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
     * @return WidgetEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): WidgetEntity
    {
        $action = new WidgetGetAction($this->id);
        $widgetEntity = $action->run();

        if ($widgetEntity) {
            $widgetEntity->status = $this->status;
            Widget::find($this->id)->update($widgetEntity->toArray());
            Cache::tags(['widget'])->flush();

            return $widgetEntity;
        }

        throw new RecordNotExistException(
            trans('widget::actions.admin.widgetUpdateStatusAction.notExistWidget')
        );
    }
}
