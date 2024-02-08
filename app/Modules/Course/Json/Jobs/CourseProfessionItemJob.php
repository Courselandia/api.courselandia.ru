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

/**
 * Задача для формирования курсов профессии.
 */
class CourseProfessionItemJob extends JsonItemJob
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
            'filters' => ['professions-id' => $this->id],
            'offset' => 0,
            'limit' => 36,
            'section' => 'profession',
            'sectionLink' => $this->link,
        ]));

        $entityCourseRead = $action->run();

        if ($entityCourseRead) {
            $data = [
                'data' => $entityCourseRead->toArray(),
                'success' => true,
            ];

            $this->save($data);
        }
    }
}
