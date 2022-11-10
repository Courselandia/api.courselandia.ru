<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Repositories\Course;
use Cache;

/**
 * Класс действия для удаления курса.
 */
class CourseDestroyAction extends Action
{
    /**
     * Репозиторий курсов.
     *
     * @var Course
     */
    private Course $course;

    /**
     * Массив ID курсов.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

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
     * @throws ParameterInvalidException
     */
    public function run(): bool
    {
        if ($this->ids) {
            $ids = $this->ids;

            for ($i = 0; $i < count($ids); $i++) {
                $this->course->destroy($ids[$i]);
            }

            Cache::tags([
                'course',
                'direction',
                'profession',
                'category',
                'skill',
                'teacher',
                'tool',
            ])->flush();
        }

        return true;
    }
}
