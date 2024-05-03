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
use App\Modules\Promocode\Models\Promocode;

/**
 * Класс действия для удаления промокода.
 */
class PromocodeDestroyAction extends Action
{
    /**
     * Массив ID промокодов.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID промокодов.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Promocode::destroy($this->ids);
            Cache::tags(['promocode', 'school'])->flush();
        }

        return true;
    }
}
