<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseReadRatedAction;

/**
 * Задача для формирования всех категорий.
 */
class RatedCoursesItemJob extends JsonItemJob
{
    /**
     * Количество выводимых курсов.
     */
    const LIMIT = 16;

    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CourseReadRatedAction(self::LIMIT);
        $data = $action->run();

        $data = [
            'data' => $data,
            'success' => true,
        ];

        $this->save($data);
    }
}
