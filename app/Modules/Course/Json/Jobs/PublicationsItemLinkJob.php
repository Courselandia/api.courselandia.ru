<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Publication\Actions\Site\PublicationReadAction;
use App\Modules\Publication\Data\Actions\Site\PublicationRead;

/**
 * Задача для формирования списка публикаций.
 */
class PublicationsItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new PublicationReadAction(PublicationRead::from([
            'offset' => 0,
            'limit' => 20,
        ]));
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
