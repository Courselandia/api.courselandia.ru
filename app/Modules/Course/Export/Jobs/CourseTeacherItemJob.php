<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Export\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseReadAction;

/**
 * Задача для формирования учителя.
 */
class CourseTeacherItemJob extends CourseItemJob
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
        $action->filters = ['teachers-id' => $this->uuid];
        $action->offset = 0;
        $action->limit = 36;
        $action->section = 'teacher';
        $action->sectionLink = $this->link;
        $action->precache = false;

        $entityCourseRead = $action->run();

        if ($entityCourseRead) {
            $this->save($entityCourseRead);
        }
    }
}
