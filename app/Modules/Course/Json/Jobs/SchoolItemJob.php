<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\School\Actions\Site\School\SchoolLinkAction;

/**
 * Задача для формирования школы.
 */
class SchoolItemJob extends JsonItemJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new SchoolLinkAction($this->link);
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
