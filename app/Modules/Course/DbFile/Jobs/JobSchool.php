<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile\Jobs;

use Cache;
use Log;
use App\Modules\Course\Actions\Site\Course\CourseReadAction;
use App\Modules\Course\DbFile\Item;

/**
 * Задача для формирования школы.
 */
class JobSchool extends JobItem
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        Cache::flush();

        $action = app(CourseReadAction::class);
        $action->sorts = ['name' => 'ASC'];
        $action->filters = ['school-id' => $this->id];
        $action->offset = 0;
        $action->limit = 36;
        $action->section = 'school';
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
