<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\CourseImage;

use Cache;
use ImageStore;
use ReflectionException;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Course\Repositories\Course;

/**
 * Класс действия для удаления изображения курса.
 */
class CourseImageDestroyAction extends Action
{
    /**
     * Репозиторий курсов.
     *
     * @var Course
     */
    private Course $course;

    /**
     * ID курса.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * @return bool Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        $query = new RepositoryQueryBuilder($this->id);
        $cacheKey = Util::getKey('course', $query);

        $course = Cache::tags(['course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->course->get($query);
            }
        );

        if ($course) {
            if ($course->image_small_id) {
                ImageStore::destroy($course->image_small_id->id);
            }

            if ($course->image_middle_id) {
                ImageStore::destroy($course->image_middle_id->id);
            }

            if ($course->image_big_id) {
                ImageStore::destroy($course->image_big_id->id);
            }

            $course->image_small_id = null;
            $course->image_middle_id = null;
            $course->image_big_id = null;

            $this->course->update($this->id, $course);
            Cache::tags(['course'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('course::actions.admin.courseImageDestroyAction.notExistCourse')
        );
    }
}
