<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile\Jobs;

use Cache;
use App\Modules\Course\Actions\Site\Course\CourseReadAction;
use App\Modules\Course\DbFile\Item;

/**
 * Задача для формирования каегории.
 */
class JobCategory extends JobItem
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
        $action->filters = ['categories-id' => $this->id];
        $action->offset = 0;
        $action->limit = 36;
        $action->section = 'category';
        $action->sectionLink = $this->link;
        $action->dbFile = false;

        $entityCourseRead = $action->run();

        if ($entityCourseRead) {
            $item = new Item();
            $item->id = $this->id;
            $item->data = $entityCourseRead;

            $this->save($item);
        }
    }
}
