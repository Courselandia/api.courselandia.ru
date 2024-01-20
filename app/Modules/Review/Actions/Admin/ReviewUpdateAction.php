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
            $reviewEntity->id = $this->data->id;
            $reviewEntity->school_id = $this->data->school_id;
            $reviewEntity->course_id = $this->data->course_id;
            $reviewEntity->name = $this->data->name;
            $reviewEntity->title = Typography::process($this->data->title, true);
            $reviewEntity->review = Typography::process($this->data->review, true);
            $reviewEntity->advantages = Typography::process($this->data->advantages, true);
            $reviewEntity->disadvantages = Typography::process($this->data->disadvantages, true);
            $reviewEntity->rating = $this->data->rating;
            $reviewEntity->status = $this->data->status;
            $reviewEntity->created_at = $this->data->created_at;
            $reviewEntity->source = $this->data->source;

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
