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
class SchoolsItemLinkJob extends JsonItemLinkJob
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
        $action = new SchoolReadAction(['name' => 'ASC']);
        $data = $action->run();

        if ($data) {
            $data['success'] = true;
            $this->save($data);
        }
    }
}
