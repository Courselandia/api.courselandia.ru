<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Review\Data\Admin\ReviewUpdate;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Models\Review;
use Cache;
use Typography;

/**
 * Класс действия для обновления отзывов.
 */
class ReviewUpdateAction extends Action
{
    /**
     * @var ReviewUpdate Данные для обновления отзыва.
     */
    private ReviewUpdate $data;

    /**
     * @param ReviewUpdate $data Данные для обновления отзыва.
     */
    public function __construct(ReviewUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ReviewEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): ReviewEntity
    {
        $action = new ReviewGetAction($this->data->id);
        $reviewEntity = $action->run();

        if ($reviewEntity) {
            $reviewEntity = ReviewEntity::from([
                ...$reviewEntity->toArray(),
                ...$this->data->toArray(),
                'title' => Typography::process($this->data->title, true),
                'review' => Typography::process($this->data->review, true),
                'advantages' => Typography::process($this->data->advantages, true),
                'disadvantages' => Typography::process($this->data->disadvantages, true),
            ]);

            Review::find($this->data->id)->update($reviewEntity->toArray());
            Cache::tags(['catalog', 'school', 'review', 'course'])->flush();

            $action = new ReviewGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('review::actions.admin.reviewUpdateAction.notExistReview')
        );
    }
}
