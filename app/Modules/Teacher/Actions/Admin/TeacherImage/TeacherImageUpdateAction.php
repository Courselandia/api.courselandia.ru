<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\TeacherImage;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherGetAction;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Cache;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения учителя.
 */
class TeacherImageUpdateAction extends Action
{
    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Тип изображения.
     *
     * @var string|null
     */
    public string|null $type = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): TeacherEntity
    {
        if ($this->id) {
            $action = app(TeacherGetAction::class);
            $action->id = $this->id;
            $teacher = $action->run();

            if ($teacher) {
                $teacher->image_small_id = $this->image;
                $teacher->image_middle_id = $this->image;

                Teacher::find($this->id)->update($teacher->toArray());
                Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.teacherImageUpdateAction.notExistTeacher'));
    }
}
