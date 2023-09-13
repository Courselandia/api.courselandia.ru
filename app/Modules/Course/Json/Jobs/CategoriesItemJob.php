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
use App\Modules\Course\Actions\Site\Course\CourseCategoryReadAction;

/**
 * Задача для формирования всех категорий.
 */
class CategoriesItemJob extends JsonItemJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        $action = app(CourseCategoryReadAction::class);
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
