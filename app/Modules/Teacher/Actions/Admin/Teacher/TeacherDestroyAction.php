<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Teacher\Repositories\Teacher;
use Cache;

/**
 * Класс действия для удаления учителя.
 */
class TeacherDestroyAction extends Action
{
    /**
     * Репозиторий учителя.
     *
     * @var Teacher
     */
    private Teacher $teacher;

    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Конструктор.
     *
     * @param  Teacher  $teacher  Репозиторий учителя.
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): bool
    {
        if ($this->ids) {
            $ids = $this->ids;

            for ($i = 0; $i < count($ids); $i++) {
                $this->teacher->destroy($ids[$i]);
            }

            Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();
        }

        return true;
    }
}
