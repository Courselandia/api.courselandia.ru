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
use App\Modules\Promotion\Entities\Promotion as PromotionEntity;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Promotion\Data\PromotionCreate;

/**
 * Класс действия для создания промоакции.
 */
class PromotionCreateAction extends Action
{
    /**
     * Данные для создания промоакции.
     *
     * @var PromotionCreate
     */
    private PromotionCreate $data;

    /**
     * @param PromotionCreate $data Данные для создания промоакции.
     */
    public function __construct(PromotionCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return PromotionEntity Вернет результаты исполнения.
     * @throws Throwable
     */
    public function run(): PromotionEntity
    {
        $promotionEntity = PromotionEntity::from([
            ...$this->data->toArray(),
            'title' => Typography::process($this->data->title, true),
            'description' => Typography::process($this->data->description, true),
        ]);

        $promotion = Promotion::create($promotionEntity->toArray());
        Cache::tags(['promotion', 'school'])->flush();

        $action = new PromotionGetAction($promotion->id);

        return $action->run();
    }
}
