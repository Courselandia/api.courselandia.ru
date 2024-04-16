<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Faq\Actions\Site\FaqReadAction;

/**
 * Задача для формирования всех FAQ's.
 */
class FaqsItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new FaqReadAction($this->link);
        $data = $action->run();
        $data['success'] = true;

        $this->save($data);
    }
}
