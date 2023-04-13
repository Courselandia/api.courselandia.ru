<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseReadAction;
use App\Modules\Course\DbFile\Item;

/**
 * Задача для формирования курсов.
 */
class JobCourse extends JobItem
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = app(CourseReadAction::class);
        $action->sorts = ['name' => 'ASC'];
        $action->filters = [];
        $action->offset = 0;
        $action->limit = 36;

        $entityCourseRead = $action->run();

        if ($entityCourseRead) {
            $item = new Item();
            $item->id = $this->id;
            $item->data = $entityCourseRead;

            $this->save($item);
        }
    }
}
