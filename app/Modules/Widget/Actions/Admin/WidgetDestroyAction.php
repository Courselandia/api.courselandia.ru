<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Actions\Admin;

use Cache;
use App\Models\Action;
use App\Modules\Widget\Models\Widget;

/**
 * Класс действия для удаления виджета.
 */
class WidgetDestroyAction extends Action
{
    /**
     * Массив ID виджетов.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID виджетов.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Widget::destroy($this->ids);
            Cache::tags(['widget'])->flush();
        }

        return true;
    }
}
