<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Actions\Admin;

use Throwable;
use Util;
use Cache;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Promocode\Entities\Promocode as PromocodeEntity;
use App\Modules\Promocode\Models\Promocode;

/**
 * Класс действия для получения промокода.
 */
class PromocodeGetAction extends Action
{
    /**
     * ID промокода.
     *
     * @var int|string
     */
    private int|string $id;

    /***
     * @param int|string $id ID промокода.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return PromocodeEntity|null Вернет результаты исполнения.
     * @throws Throwable
     */
    public function run(): ?PromocodeEntity
    {
        $cacheKey = Util::getKey('promocode', $this->id);

        return Cache::tags(['promocode', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $promocode = Promocode::where('id', $this->id)
                    ->with([
                        'school',
                    ])
                    ->first();

                if ($promocode) {
                    $promocode = PromocodeEntity::from($promocode->toArray());

                    $action = new PromocodeApplicableAction($promocode->id);
                    $promocode->applicable = $action->run();

                    return $promocode;
                }

                return null;
            }
        );
    }
}
