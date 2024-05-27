<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Actions\Admin;

use Util;
use Cache;
use Carbon\Carbon;
use Throwable;
use App\Models\Enums\CacheTime;
use App\Models\Action;
use App\Modules\Promocode\Models\Promocode;
use App\Models\Exceptions\RecordNotExistException;

/**
 * Класс действия для определения, что промокод до сих пор действует.
 */
class PromocodeApplicableAction extends Action
{
    /**
     * ID промокода.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID промокода.
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
        $cacheKey = Util::getKey('promocode', 'applicable', $this->id);

        $promocode = Cache::tags(['promocode', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return Promocode::where('id', $this->id)
                    ->first();
            }
        );

        if ($promocode) {
            if ($promocode->status
                && (
                    (!$promocode->date_start && !$promocode->date_end)
                    || (Carbon::now() >= $promocode->date_start && !$promocode->date_end)
                    || (!$promocode->date_start && Carbon::now()->startOfDay() <= $promocode->date_end)
                    || (Carbon::now() >= $promocode->date_start && Carbon::now()->startOfDay() <= $promocode->date_end)
                )
            ) {
                return true;
            }

            return false;
        }

        throw new RecordNotExistException(
            trans('promocode::actions.admin.promocodeUpdateAction.notExistPromocode')
        );
    }
}
