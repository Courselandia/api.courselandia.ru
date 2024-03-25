<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\Collection;

use App\Models\Action;
use App\Modules\Collection\Models\Collection;
use Cache;

/**
 * Класс действия для удаления коллекции.
 */
class CollectionDestroyAction extends Action
{
    /**
     * Массив ID коллекций.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param array $ids Массив ID коллекций.
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
            Collection::destroy($this->ids);
            Cache::tags(['catalog', 'collection'])->flush();
        }

        return true;
    }
}
