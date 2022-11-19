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
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

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
