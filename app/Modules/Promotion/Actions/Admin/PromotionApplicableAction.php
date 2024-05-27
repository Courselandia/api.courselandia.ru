<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Actions\Admin;

use Util;
use Cache;
use Carbon\Carbon;
use Throwable;
use App\Models\Enums\CacheTime;
use App\Models\Action;
use App\Modules\Promotion\Models\Promotion;
use App\Models\Exceptions\RecordNotExistException;

/**
 * Класс действия для определения, что промоакция до сих пор действует.
 */
class PromotionApplicableAction extends Action
{
    /**
     * ID промоакции.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID промоакции.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws Throwable
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('promotion', 'applicable', $this->id);

        $promotion = Cache::tags(['promotion', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return Promotion::where('id', $this->id)
                    ->first();
            }
        );

        if ($promotion) {
            if ($promotion->status
                && (
                    (!$promotion->date_start && !$promotion->date_end)
                    || (Carbon::now() >= $promotion->date_start && !$promotion->date_end)
                    || (!$promotion->date_start && Carbon::now()->startOfDay() <= $promotion->date_end)
                    || (Carbon::now() >= $promotion->date_start && Carbon::now()->startOfDay() <= $promotion->date_end)
                )
            ) {
                return true;
            }

            return false;
        }

        throw new RecordNotExistException(
            trans('promotion::actions.admin.promotionUpdateAction.notExistPromotion')
        );
    }
}
