<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Promo\Actions\Site\PromoReadAction;

/**
 * Задача для формирования списка промоматериалов.
 */
class PromosItemLinkJob extends JsonItemLinkJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = new PromoReadAction();
        $data = $action->run();

        if ($data) {
            $data['success'] = true;

            $this->save($data);
        }
    }
}
