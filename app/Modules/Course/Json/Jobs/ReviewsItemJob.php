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
use App\Modules\Review\Data\Site\ReviewRead;

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
        $action = new ReviewReadAction(ReviewRead::from([
            'sorts' => ['created_at' => 'DESC'],
            'offset' => 0,
            'limit' => 20,
            'link' => $this->link,
        ]));

        $data = $action->run();
        $data['success'] = true;

        $this->save($data);
    }
}
