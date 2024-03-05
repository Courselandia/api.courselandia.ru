<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Cache;

/**
 * Класс действия для обновления статуса учителя.
 */
class TeacherUpdateStatusAction extends Action
{
    /**
     * ID учителя.
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
     * @param int|string $id ID учителя.
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
     * @return TeacherEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): TeacherEntity
    {
        $action = new TeacherGetAction($this->id);
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            $teacherEntity->status = $this->status;
            Teacher::find($this->id)->update($teacherEntity->toArray());
            Cache::tags(['catalog', 'teacher'])->flush();

            return $teacherEntity;
        }

        throw new RecordNotExistException(
            trans('teacher::actions.admin.teacherUpdateStatusAction.notExistTeacher')
        );
    }
}
