<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Models\Action;
use App\Modules\School\Models\School;
use Cache;

/**
 * Класс действия для удаления школы.
 */
class SchoolDestroyAction extends Action
{
    /**
     * Массив ID школ.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID школ.
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
            School::destroy($this->ids);
            Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->flush();
        }

        return true;
    }
}
