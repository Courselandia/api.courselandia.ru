<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Actions\Admin;

use DB;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Modules\Widget\Models\Widget;
use App\Modules\Widget\Data\WidgetValue;
use App\Modules\Widget\Data\WidgetUpdate;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Widget\Entities\Widget as WidgetEntity;
use App\Modules\Widget\Entities\WidgetValue as WidgetValueEntity;
use App\Modules\Widget\Models\WidgetValue as WidgetValueModel;

/**
 * Класс действия для обновления виджета.
 */
class WidgetUpdateAction extends Action
{
    /**
     * @var WidgetUpdate Данные для создания виджета.
     */
    private WidgetUpdate $data;

    /**
     * @param WidgetUpdate $data Данные для создания виджета.
     */
    public function __construct(WidgetUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return WidgetEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): WidgetEntity
    {
        $action = new WidgetGetAction($this->data->id);
        $widgetEntity = $action->run();

        if ($widgetEntity) {
            DB::transaction(function () use ($widgetEntity) {
                $widgetEntity = WidgetEntity::from([
                    ...$widgetEntity->toArray(),
                    ...$this->data->toArray(),
                    'name' => Typography::process($this->data->name, true),
                ]);

                Widget::find($this->data->id)->update($widgetEntity->toArray());

                WidgetValueModel::whereIn('id', collect($widgetEntity->values)->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->data->values) {
                    foreach ($this->data->values as $value) {
                        /**
                         * @var WidgetValue $value
                         */
                        $entity = WidgetValueEntity::from([
                            ...$value->toArray(),
                            'widget_id' => $this->data->id,
                        ]);

                        WidgetValueModel::create($entity->toArray());
                    }
                }

                Cache::tags(['widget'])->flush();
            });

            $action = new WidgetGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('widget::actions.admin.widgetUpdateAction.notExistWidget')
        );
    }
}
