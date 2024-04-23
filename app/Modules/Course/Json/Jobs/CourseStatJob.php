<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Course\Actions\Site\Course\CourseStatAction;

/**
 * Задача для формирования статистики.
 */
class CourseStatJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CourseStatAction();
        $stat = $action->run();

        $data = [
            'data' => [
                'amountCourses' => $stat->getAmountCourses(),
                'amountSchools' => $stat->getAmountSchools(),
                'amountTeachers' => $stat->getAmountTeachers(),
                'amountReviews' => $stat->getAmountReviews(),
            ],
            'success' => true,
        ];

        $this->save($data);
    }
}
