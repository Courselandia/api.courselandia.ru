<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\TeacherImage;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherGetAction;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения учителя.
 */
class TeacherImageUpdateAction extends Action
{
    /**
     * ID учителя.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Изображение.
     *
     * @var UploadedFile
     */
    private UploadedFile $image;

    /**
     * @param int|string $id ID учителя.
     * @param UploadedFile $image Изображение.
     */
    public function __construct(int|string $id, UploadedFile $image)
    {
        $this->id = $id;
        $this->image = $image;
    }

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): TeacherEntity
    {
        if ($this->id) {
            $action = new TeacherGetAction($this->id);
            $teacher = $action->run();

            if ($teacher) {
                $teacher = $teacher->toArray();
                $teacher['image_small_id'] = $this->image;
                $teacher['image_middle_id'] = $this->image;
                $teacher['image_big_id'] = $this->image;

                Teacher::find($this->id)->update($teacher);
                Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.teacherImageUpdateAction.notExistTeacher'));
    }
}
