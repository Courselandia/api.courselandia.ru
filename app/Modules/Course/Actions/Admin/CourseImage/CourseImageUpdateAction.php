<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\CourseImage;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Actions\Admin\Course\CourseGetAction;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Models\Course;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения курса.
 */
class CourseImageUpdateAction extends Action
{
    /**
     * ID курса.
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
     * @param int|string $id ID курса.
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
     * @return CourseEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): CourseEntity
    {
        if ($this->id) {
            $action = new CourseGetAction($this->id);
            $course = $action->run();

            if ($course) {
                $course = $course->toArray();
                $course['image_small_id'] = $this->image;
                $course['image_middle_id'] = $this->image;
                $course['image_big_id'] = $this->image;

                Course::find($this->id)->update($course);
                Cache::tags(['catalog', 'course'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('course::actions.admin.courseImageUpdateAction.notExistCourse'));
    }
}
