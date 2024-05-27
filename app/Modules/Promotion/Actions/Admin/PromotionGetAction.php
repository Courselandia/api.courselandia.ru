<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Actions\Admin;

use Throwable;
use Util;
use Cache;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Promotion\Entities\Promotion as PromotionEntity;
use App\Modules\Promotion\Models\Promotion;

/**
 * Класс действия для получения промоакции.
 */
class PromotionGetAction extends Action
{
    /**
     * ID промоакции.
     *
     * @var int|string
     */
    private int|string $id;

    /***
     * @param int|string $id ID промоакции.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return PromotionEntity|null Вернет результаты исполнения.
     * @throws Throwable
     */
    public function run(): ?PromotionEntity
    {
        $cacheKey = Util::getKey('promotion', $this->id);

        return Cache::tags(['promotion', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $promotion = Promotion::where('id', $this->id)
                    ->with([
                        'school',
                    ])
                    ->first();

                if ($promotion) {
                    $promotion = PromotionEntity::from($promotion->toArray());

                    $action = new PromotionApplicableAction($promotion->id);
                    $promotion->applicable = $action->run();

                    return $promotion;
                }

                return null;
            }
        );
    }
}
