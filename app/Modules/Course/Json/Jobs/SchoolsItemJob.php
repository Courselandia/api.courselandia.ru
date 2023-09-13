<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use ReflectionException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\School\Actions\Site\School\SchoolReadAction;

/**
 * Задача для формирования всех школ.
 */
class SchoolsItemJob extends JsonItemJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function handle(): void
    {
        $action = app(SchoolReadAction::class);
        $data = $action->run();

        if ($data) {
            $data['success'] = true;
            $this->save($data);
        }
    }
}
