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
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;

/**
 * Нормализация для изображений школ.
 */
class SchoolWorker extends Worker
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
        $schools = $this->getQuery()->get();

        foreach ($schools as $school) {
            $imageLogo = $school->image_logo_id ? json_encode($school->image_logo_id) : null;
            $imageSite = $school->image_site_id ? json_encode($school->image_site_id) : null;

            if (
                $imageLogo !== $school->image_logo
                || $imageSite !== $school->image_site
            ) {
                $school->image_logo = $imageLogo;
                $school->image_site = $imageSite;

                $school->save();
            }

            $this->fireEvent('normalized', [$school]);
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return School::whereHas('courses', function ($query) {
                $query->select([
                    'courses.id',
                ])
                ->where('status', Status::ACTIVE->value);
            })
            ->where('status', true)
            ->orderBy('name');
    }
}