<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Profession\Actions\Site\ProfessionLinkAction;

/**
 * Задача для формирования профессии.
 */
class ProfessionItemJob extends JsonItemJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        $action = new ProfessionLinkAction($this->link);
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
