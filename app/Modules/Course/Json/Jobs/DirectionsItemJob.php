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
use App\Modules\Course\Actions\Site\Course\CourseDirectionReadAction;

/**
 * Задача для формирования всех направлений.
 */
class DirectionsItemJob extends JsonItemJob
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
        $action = app(CourseDirectionReadAction::class);
        $action->withCategories = true;
        $action->withCount = true;
        $data = $action->run();

        if ($data) {
            $data = [
                'data' => $data,
                'success' => true,
            ];

            $this->save($data);
        }
    }
}
