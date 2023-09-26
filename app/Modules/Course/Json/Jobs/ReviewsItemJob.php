<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Jobs;

use App\Modules\Review\Actions\Site\ReviewReadAction;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Задача для формирования всех отзывов.
 */
class ReviewsItemJob extends JsonItemJob
{
    /**
     * Выполнение задачи.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function handle(): void
    {
        $action = app(ReviewReadAction::class);
        $action->sorts = ['created_at' => 'DESC'];
        $action->offset = 0;
        $action->limit = 20;
        $action->link = $this->link;

        $data = $action->run();
        $data['success'] = true;

        $this->save($data);
    }
}
