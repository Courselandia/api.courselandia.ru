<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Faq\Actions\Site\FaqReadAction;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Задача для формирования всех FAQ's.
 */
class FaqsItemJob extends JsonItemJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        $action = new FaqReadAction($this->link);
        $data = $action->run();
        $data['success'] = true;

        $this->save($data);
    }
}
