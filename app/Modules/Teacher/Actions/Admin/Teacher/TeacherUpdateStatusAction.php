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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): TeacherEntity
    {
        $action = app(TeacherGetAction::class);
        $action->id = $this->id;
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            $teacherEntity->status = $this->status;
            Teacher::find($this->id)->update($teacherEntity->toArray());
            Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

            return $teacherEntity;
        }

        throw new RecordNotExistException(
            trans('teacher::actions.admin.teacherUpdateStatusAction.notExistTeacher')
        );
    }
}
