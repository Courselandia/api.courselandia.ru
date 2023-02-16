<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use App\Models\Action;
use App\Modules\Course\Models\Course;
use Cache;

/**
 * Класс действия для удаления курса.
 */
class CourseDestroyAction extends Action
{
    /**
     * Массив ID курсов.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Course::destroy($this->ids);

            Cache::tags([
                'course',
                'direction',
                'profession',
                'category',
                'skill',
                'teacher',
                'tool',
                'process',
                'employment',
                'review',
            ])->flush();
        }

        return true;
    }
}
