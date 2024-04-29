<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Actions\Admin;

use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Promotion\Entities\Promotion as PromotionEntity;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Promotion\Data\PromotionUpdate;

/**
 * Класс действия для обновления промоакции.
 */
class PromotionUpdateAction extends Action
{
    /**
     * @var PromotionUpdate Данные для создания промоакции.
     */
    private PromotionUpdate $data;

    /**
     * @param PromotionUpdate $data Данные для создания промоакции.
     */
    public function __construct(PromotionUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return PromotionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws Throwable
     */
    public function run(): PromotionEntity
    {
        $action = new PromotionGetAction($this->data->id);
        $promotionEntity = $action->run();

        if ($promotionEntity) {
            $promotionEntity = PromotionEntity::from([
                ...$promotionEntity->toArray(),
                ...$this->data->toArray(),
                'title' => Typography::process($this->data->title, true),
                'description' => Typography::process($this->data->description, true),
            ]);

            Promotion::find($this->data->id)->update($promotionEntity->toArray());
            Cache::tags(['catalog', 'school'])->flush();

            $action = new PromotionGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('promotion::actions.admin.promotionUpdateAction.notExistPromotion')
        );
    }
}
