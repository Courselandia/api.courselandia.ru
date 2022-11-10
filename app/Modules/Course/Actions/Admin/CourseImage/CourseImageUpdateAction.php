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
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Actions\Admin\Course\CourseGetAction;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Repositories\Course;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения курса.
 */
class CourseImageUpdateAction extends Action
{
    /**
     * Репозиторий курсов.
     *
     * @var Course
     */
    private Course $course;

    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * Конструктор.
     *
     * @param  Course  $course  Репозиторий курсов.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): CourseEntity
    {
        if ($this->id) {
            $action = app(CourseGetAction::class);
            $action->id = $this->id;
            $course = $action->run();

            if ($course) {
                $course->image_small_id = $this->image;
                $course->image_middle_id = $this->image;
                $course->image_big_id = $this->image;
                $this->course->update($this->id, $course);
                Cache::tags(['course'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('course::actions.admin.courseImageUpdateAction.notExistCourse'));
    }
}
