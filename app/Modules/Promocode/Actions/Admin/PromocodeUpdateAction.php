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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Promocode\Entities\Promocode as PromocodeEntity;
use App\Modules\Promocode\Models\Promocode;
use App\Modules\Promocode\Data\PromocodeUpdate;

/**
 * Класс действия для обновления промокода.
 */
class PromocodeUpdateAction extends Action
{
    /**
     * @var PromocodeUpdate Данные для создания промокода.
     */
    private PromocodeUpdate $data;

    /**
     * @param PromocodeUpdate $data Данные для создания промокода.
     */
    public function __construct(PromocodeUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return PromocodeEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws Throwable
     */
    public function run(): PromocodeEntity
    {
        $action = new PromocodeGetAction($this->data->id);
        $promocodeEntity = $action->run();

        if ($promocodeEntity) {
            $promocodeEntity = PromocodeEntity::from([
                ...$promocodeEntity->toArray(),
                ...$this->data->toArray(),
                'title' => Typography::process($this->data->title, true),
                'description' => Typography::process($this->data->description, true),
            ]);

            Promocode::find($this->data->id)->update($promocodeEntity->toArray());
            Cache::tags(['promocode', 'school'])->flush();

            $action = new PromocodeGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('promocode::actions.admin.promocodeUpdateAction.notExistPromocode')
        );
    }
}
