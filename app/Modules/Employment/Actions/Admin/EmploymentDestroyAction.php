<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use App\Models\Action;
use App\Modules\Employment\Models\Employment;
use Cache;

/**
 * Класс действия для удаления трудоустройства.
 */
class EmploymentDestroyAction extends Action
{
    /**
     * Массив ID трудоустройства.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID трудоустройства.
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
            Employment::destroy($this->ids);
            Cache::tags(['catalog', 'employment'])->flush();
        }

        return true;
    }
}
