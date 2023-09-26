<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Actions\Site\Course\CourseReadAction;

/**
 * Задача для формирования курсов профессии.
 */
class CourseProfessionItemJob extends JsonItemJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        $action = app(CourseReadAction::class);
        $action->sorts = ['name' => 'ASC'];
        $action->filters = ['professions-id' => $this->id];
        $action->offset = 0;
        $action->limit = 36;
        $action->section = 'profession';
        $action->sectionLink = $this->link;

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
