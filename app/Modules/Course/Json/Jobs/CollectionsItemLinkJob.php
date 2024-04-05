<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Collection\Actions\Site\CollectionReadAction;

/**
 * Задача для формирования списка коллекций.
 */
class CollectionsItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CollectionReadAction($this->id, 0, 30);
        $data = $action->run();

        if ($data) {
            $data['success'] = true;

            $this->save($data);
        }
    }
}
