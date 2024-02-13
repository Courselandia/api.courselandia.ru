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
    private array $ids;

    /**
     * Массив ID курсов.
     *
     * @param array $ids
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

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
