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
use App\Modules\Promotion\Models\Promotion;

/**
 * Класс действия для удаления промоакции.
 */
class PromotionDestroyAction extends Action
{
    /**
     * Массив ID промоакций.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID промоакций.
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
            Promotion::destroy($this->ids);
            Cache::tags(['promotion', 'school'])->flush();
        }

        return true;
    }
}
