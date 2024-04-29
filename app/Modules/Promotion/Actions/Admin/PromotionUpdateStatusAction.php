<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Actions\Admin;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Promotion\Entities\Promotion as PromotionEntity;
use App\Modules\Promotion\Models\Promotion;

/**
 * Класс действия для обновления статуса промоакции.
 */
class PromotionUpdateStatusAction extends Action
{
    /**
     * ID промоакции.
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
     * @param int|string $id ID промоакции.
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
     * @return PromotionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): PromotionEntity
    {
        $action = new PromotionGetAction($this->id);
        $promotionEntity = $action->run();

        if ($promotionEntity) {
            $promotionEntity->status = $this->status;
            Promotion::find($this->id)->update($promotionEntity->toArray());
            Cache::tags(['promotion', 'school'])->flush();

            return $promotionEntity;
        }

        throw new RecordNotExistException(
            trans('promotion::actions.admin.promotionUpdateStatusAction.notExistPromotion')
        );
    }
}
