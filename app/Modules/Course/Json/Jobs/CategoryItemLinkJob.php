<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Category\Actions\Site\CategoryLinkAction;

/**
 * Задача для формирования категорий.
 */
class CategoryItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CategoryLinkAction($this->link);
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
