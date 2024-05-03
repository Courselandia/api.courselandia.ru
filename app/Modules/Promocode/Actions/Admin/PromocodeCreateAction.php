<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Actions\Admin;

use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Modules\Promocode\Entities\Promocode as PromocodeEntity;
use App\Modules\Promocode\Models\Promocode;
use App\Modules\Promocode\Data\PromocodeCreate;

/**
 * Класс действия для создания промокода.
 */
class PromocodeCreateAction extends Action
{
    /**
     * Данные для создания промокода.
     *
     * @var PromocodeCreate
     */
    private PromocodeCreate $data;

    /**
     * @param PromocodeCreate $data Данные для создания промокода.
     */
    public function __construct(PromocodeCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return PromocodeEntity Вернет результаты исполнения.
     * @throws Throwable
     */
    public function run(): PromocodeEntity
    {
        $promocodeEntity = PromocodeEntity::from([
            ...$this->data->toArray(),
            'title' => Typography::process($this->data->title, true),
            'description' => Typography::process($this->data->description, true),
        ]);

        $promocode = Promocode::create($promocodeEntity->toArray());
        Cache::tags(['promocode', 'school'])->flush();

        $action = new PromocodeGetAction($promocode->id);

        return $action->run();
    }
}
