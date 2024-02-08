<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseGetAction;

/**
 * Задача для формирования каегории.
 */
class CourseItemJob extends JsonItemJob
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
                'data' => $data->toArray(),
                'success' => true,
            ];

            $this->save($data);
        }
    }
}
