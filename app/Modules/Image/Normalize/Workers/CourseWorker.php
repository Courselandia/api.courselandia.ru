<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Normalize\Workers;

use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Image\Normalize\Worker;
use Illuminate\Database\Eloquent\Builder;

/**
 * Нормализация для изображений курсов.
 */
class CourseWorker extends Worker
{
    /**
     * Вернет общее количество обрабатываемых записей.
     *
     * @return int Количество записей.
     */
    public function total(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Процесс нормализации.
     *
     * @return void.
     */
    public function run(): void
    {
        $courses = $this->getQuery()->get();

        foreach ($courses as $course) {
            $imageSmall = $course->image_small_id ? json_encode($course->image_small_id) : null;
            $imageMiddle = $course->image_middle_id ? json_encode($course->image_middle_id) : null;
            $imageBig = $course->image_big_id ? json_encode($course->image_big_id) : null;

            if (
                $imageSmall !== $course->image_small
                || $imageMiddle !== $course->image_middle
                || $imageBig !== $course->image_big
            ) {
                $course->image_small = $imageSmall;
                $course->image_middle = $imageMiddle;
                $course->image_big = $imageBig;

                $course->save();
            }

            $this->fireEvent('normalized', [$course]);
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Course::where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->where('status', true);
            });
    }
}