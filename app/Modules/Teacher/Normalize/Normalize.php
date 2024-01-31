<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Normalize;

use Throwable;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Teacher\Models\Teacher;

/**
 * Нормализация учителей.
 */
class Normalize
{
    use Event;
    use Error;

    /**
     * Получение количества учителей для нормализации.
     *
     * @return int Количество учителей.
     */
    public function getTotal(): int
    {
        return Teacher::count();
    }

    /**
     * Запуск нормализации.
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
        $teachers = Teacher::with([
            'courses.directions',
            'courses.school',
        ])
            ->get();

        foreach ($teachers as $teacher) {
            $directions = [];
            $schools = [];
            $rating = 0;
            $oldDirectionsCount = count($teacher->directions);
            $oldSchoolsCount = count($teacher->schools);

            foreach ($teacher->courses as $course) {
                $rating += $course->rating;

                foreach ($course->directions as $direction) {
                    if (!in_array($direction->id, $directions)) {
                        $directions[] = $direction->id;
                    }
                }

                if ($course->school?->id && !in_array($course->school->id, $schools)) {
                    $schools[] = $course->school->id;
                }
            }

            $teacher->directions()->sync($directions);
            $teacher->schools()->sync($schools);
            $ratingNew = count($teacher->courses) ? $rating / count($teacher->courses) : 0;

            try {
                if ($oldDirectionsCount !== count($directions) || $oldSchoolsCount !== count($schools)) {
                    $teacher->rating = round($ratingNew, 2);
                    $teacher->save();
                }

                $this->fireEvent('normalized', [$teacher]);
            } catch (Throwable $error) {
                $this->addError($error);
            }
        }
    }
}
