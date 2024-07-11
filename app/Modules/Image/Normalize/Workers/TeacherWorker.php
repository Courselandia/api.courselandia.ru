<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Normalize\Workers;

use App\Modules\Course\Enums\Status;
use App\Modules\Image\Normalize\Worker;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;

/**
 * Нормализация для изображений учителей.
 */
class TeacherWorker extends Worker
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
        $teachers = $this->getQuery()->get();

        foreach ($teachers as $teacher) {
            $imageSmall = $teacher->image_small_id ? json_encode($teacher->image_small_id) : null;
            $imageMiddle = $teacher->image_middle_id ? json_encode($teacher->image_middle_id) : null;
            $imageBig = $teacher->image_big_id ? json_encode($teacher->image_big_id) : null;

            if (
                $imageSmall !== $teacher->image_small
                || $imageMiddle !== $teacher->image_middle
                || $imageBig !== $teacher->image_big
            ) {
                $teacher->image_small = $imageSmall;
                $teacher->image_middle = $imageMiddle;
                $teacher->image_big = $imageBig;

                $teacher->save();
            }

            $this->fireEvent('normalized', [$teacher]);
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Teacher::whereHas('courses', function ($query) {
            $query->select([
                'courses.id',
            ])
            ->where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->active()
                    ->hasCourses();
            });
        })
        ->where('status', true)
        ->orderBy('name');
    }
}