<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseReadRatedAction;
use App\Modules\Course\Helpers\CleanCourseList;

/**
 * Задача для формирования всех категорий.
 */
class RatedCoursesItemLinkJob extends JsonItemLinkJob
{
    /**
     * Количество выводимых курсов.
     */
    const int LIMIT = 16;

    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CourseReadRatedAction(self::LIMIT);
        $data = $action->run();
        $data = CleanCourseList::do($data);

        $data = [
            'data' => $data,
            'success' => true,
        ];

        $this->save($data);
    }
}
