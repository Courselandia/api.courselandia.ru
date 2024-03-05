<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Models\Action;
use App\Modules\Teacher\Models\Teacher;
use Cache;

/**
 * Класс действия для удаления учителя.
 */
class TeacherDestroyAction extends Action
{
    /**
     * Массив ID учителей.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param array $ids Массив ID учителей.
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
            Teacher::destroy($this->ids);
            Cache::tags(['catalog', 'teacher'])->flush();
        }

        return true;
    }
}
