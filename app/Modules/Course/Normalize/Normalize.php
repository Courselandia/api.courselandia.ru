<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Normalize;

use Throwable;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;

/**
 * Нормализация каталога курсов.
 */
class Normalize
{
    use Event;
    use Error;

    /**
     * Получение количество курсов для нормализации.
     *
     * @return int Количество курсов.
     */
    public function getTotal(): int
    {
        return Course::where('status', Status::ACTIVE->value)
            ->count();
    }

    /**
     * Нормализация каталога курсов.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->do();
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Проводим нормализацию.
     *
     * @return void
     */
    private function do(): void
    {
        $courses = Course::with([
            'directions' => function ($query) {
                $query->where('status', true);
            },
            'professions' => function ($query) {
                $query->where('status', true);
            },
            'categories' => function ($query) {
                $query->where('status', true);
            },
            'skills' => function ($query) {
                $query->where('status', true);
            },
            'teachers' => function ($query) {
                $query->where('status', true);
            },
            'tools' => function ($query) {
                $query->where('status', true);
            },
            'school' => function ($query) {
                $query->where('status', true);
            },
            'levels',
        ])
        ->where('status', Status::ACTIVE->value)
        ->get();

        foreach ($courses as $course) {
            $sourceCourse = $course->replicate();
            $course->direction_ids = $course->directions->pluck('id');
            $course->profession_ids = $course->professions->pluck('id');
            $course->category_ids = $course->categories->pluck('id');
            $course->skill_ids = $course->skills->pluck('id');
            $course->teacher_ids = $course->teachers->pluck('id');
            $course->tool_ids = $course->tools->pluck('id');
            $course->level_values = Data::getLevels($course->levels->pluck('level')->toArray());
            $course->has_active_school = (bool)$course->school;

            try {
                if ($this->hasToBeChanged($sourceCourse, $course)) {
                    $course->save();
                }

                $this->fireEvent('normalized', [$course]);
            } catch (Throwable $error) {
                $this->addError($error);
            }
        }
    }

    /**
     * Проверка нужно ли обновлять курс.
     *
     * @param Course $sourceCourse Изначальный курс.
     * @param Course $targetCourse Полученный курс после изменений.
     *
     * @return bool Вернет результат проверки.
     */
    private function hasToBeChanged(Course $sourceCourse, Course $targetCourse): bool
    {
        return $sourceCourse->direction_ids !== $targetCourse->direction_ids
            || $sourceCourse->profession_ids !== $targetCourse->profession_ids
            || $sourceCourse->category_ids !== $targetCourse->category_ids
            || $sourceCourse->skill_ids !== $targetCourse->skill_ids
            || $sourceCourse->teacher_ids !== $targetCourse->teacher_ids
            || $sourceCourse->tool_ids !== $targetCourse->tool_ids
            || $sourceCourse->level_values !== $targetCourse->level_values
            || $sourceCourse->has_active_school !== $targetCourse->has_active_school;
    }
}
