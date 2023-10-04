<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use Cache;
use App\Models\Action;
use App\Modules\Teacher\Models\Teacher;

/**
 * Класс действия для отсоединения курсов от учителя.
 */
class TeacherDetachCoursesAction extends Action
{
    /**
     *  ID учителя.
     *
     * @var int|string|null
     */
    public string|int|null $id = null;

    /**
     * Массив ID курсов.
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
        if ($this->id && $this->ids) {
            $teacher = Teacher::find($this->id);

            if ($teacher) {
                for ($i = 0; $i < count($this->ids); $i++) {
                    $teacher->courses()->detach($this->ids[$i]);
                }
            }

            Cache::tags(['catalog', 'teacher', 'course'])->flush();
        }

        return true;
    }
}
