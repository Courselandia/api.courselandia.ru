<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseReadAction;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Helpers\CleanCourseRead;

/**
 * Задача для формирования курсов учителя.
 */
class CourseTeacherItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CourseReadAction(CourseRead::from([
            'sorts' => ['name' => 'ASC'],
            'filters' => ['teachers-id' => $this->id],
            'offset' => 0,
            'limit' => 21,
            'section' => 'teacher',
            'sectionLink' => $this->link,
        ]));

        $entityCourseRead = $action->run();
        $data = CleanCourseRead::do($entityCourseRead->toArray());

        if ($data) {
            $data = [
                'data' => $data,
                'success' => true,
            ];

            $this->save($data);
        }
    }
}
