<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Actions\Admin;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Widget\Models\Widget;
use App\Modules\Widget\Entities\Widget as WidgetEntity;

/**
 * Класс действия для получения виджета.
 */
class WidgetGetAction extends Action
{
    /**
     * ID виджета.
     *
     * @var int|string
     */
    private int|string $id;

    /***
     * @param int|string $id ID виджета.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return WidgetEntity|null Вернет результаты исполнения.
     */
    public function run(): ?WidgetEntity
    {
        $cacheKey = Util::getKey('widget', $this->id);

        return Cache::tags(['widget'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $widget = Widget::where('id', $this->id)
                    ->with([
                        'values',
                    ])
                    ->first();

                if ($widget) {
                    $data = $widget->toArray();
                    $values = $data['values'];
                    $data['values'] = [];

                    for ($i = 0; $i < count($values); $i++) {
                        $data['values'][$values[$i]['name']] = $values[$i]['value'];
                    }

                    return WidgetEntity::from($data);
                }

                return null;
            }
        );
    }
}
