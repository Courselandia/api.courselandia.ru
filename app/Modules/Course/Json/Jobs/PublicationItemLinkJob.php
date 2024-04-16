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
 * Задача для формирования публикации.
 */
class PublicationItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new PublicationReadAction(PublicationRead::from([
            'link' => $this->link,
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
