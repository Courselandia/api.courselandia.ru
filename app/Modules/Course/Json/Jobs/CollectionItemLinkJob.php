<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Collection\Actions\Site\CollectionLinkAction;
use App\Modules\Collection\Helpers\CleanCourseCollectionRead;

/**
 * Задача для формирования коллекции.
 */
class CollectionItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new CollectionLinkAction($this->link);
        $collection = $action->run();

        if ($collection) {
            $data = $collection->toArray();
            $data['courses'] = CleanCourseCollectionRead::do($data['courses']);

            $data = [
                'data' => $data,
                'success' => true,
            ];

            $this->save($data);
        }
    }
}
