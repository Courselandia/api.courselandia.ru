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
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Widget\Entities\Widget as WidgetEntity;
use App\Modules\Widget\Models\Widget;
use App\Modules\Widget\Data\WidgetCreate;
use App\Modules\Widget\Data\WidgetValue;
use App\Modules\Widget\Entities\WidgetValue as WidgetValueEntity;
use App\Modules\Widget\Models\WidgetValue as WidgetValueModel;

/**
 * Класс действия для создания виджета.
 */
class WidgetCreateAction extends Action
{
    /**
     * Данные для создания виджета.
     *
     * @var WidgetCreate
     */
    private WidgetCreate $data;

    /**
     * @param WidgetCreate $data Данные для создания виджета.
     */
    public function __construct(WidgetCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return WidgetEntity Вернет результаты исполнения.
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): WidgetEntity
    {
        $id = DB::transaction(function () {
            $widgetEntity = WidgetEntity::from([
                ...$this->data->toArray(),
                'name' => Typography::process($this->data->name, true),
            ]);

            $widget = Widget::create($widgetEntity->toArray());

            if ($this->data->values) {
                foreach ($this->data->values as $value) {
                    /**
                     * @var WidgetValue $value
                     */
                    $entity = WidgetValueEntity::from([
                        ...$value->toArray(),
                        'widget_id' => $widget->id,
                    ]);

                    WidgetValueModel::create($entity->toArray());
                }
            }

            Cache::tags(['widget'])->flush();

            return $widget->id;
        });

        $action = new WidgetGetAction($id);

        return $action->run();
    }
}
