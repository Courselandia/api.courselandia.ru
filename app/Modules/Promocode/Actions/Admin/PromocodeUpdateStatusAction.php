<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Actions\Admin;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Promocode\Entities\Promocode as PromocodeEntity;
use App\Modules\Promocode\Models\Promocode;
use Throwable;

/**
 * Класс действия для обновления статуса промокода.
 */
class PromocodeUpdateStatusAction extends Action
{
    /**
     * ID промокода.
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
     * @param int|string $id ID промокода.
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
     * @return PromocodeEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|Throwable
     */
    public function run(): PromocodeEntity
    {
        $action = new PromocodeGetAction($this->id);
        $promocodeEntity = $action->run();

        if ($promocodeEntity) {
            $promocodeEntity->status = $this->status;
            Promocode::find($this->id)->update($promocodeEntity->toArray());
            Cache::tags(['promocode', 'school'])->flush();

            return $promocodeEntity;
        }

        throw new RecordNotExistException(
            trans('promocode::actions.admin.promocodeUpdateStatusAction.notExistPromocode')
        );
    }
}
