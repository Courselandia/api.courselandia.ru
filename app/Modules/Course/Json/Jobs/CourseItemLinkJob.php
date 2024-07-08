<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseGetAction;
use App\Modules\Course\Helpers\CleanCourse;

/**
 * Задача для формирования курсов.
 */
class CourseItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CourseGetAction(null, null, $this->id);
        $data = $action->run();

        if ($data) {
            $data = [
                'data' => CleanCourse::do($data->toArray()),
                'success' => true,
            ];

            $this->save($data);
        }
    }
}
