<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Admin;

use App\Models\Action;
use App\Modules\Review\Data\Admin\ReviewCreate;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Models\Review;
use Cache;
use Typography;

/**
 * Класс действия для создания отзывов.
 */
class ReviewCreateAction extends Action
{
    /**
     * Данные для создания отзыва.
     *
     * @var ReviewCreate
     */
    private ReviewCreate $data;

    /**
     * @param ReviewCreate $data Данные для создания отзыва.
     */
    public function __construct(ReviewCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return ReviewEntity Вернет результаты исполнения.
     */
    public function run(): ReviewEntity
    {
        $reviewEntity = ReviewEntity::from([
            ...$this->data->toArray(),
            'title' => Typography::process($this->data->title, true),
            'review' => Typography::process($this->data->review, true),
            'advantages' => Typography::process($this->data->advantages, true),
            'disadvantages' => Typography::process($this->data->disadvantages, true)
        ]);

        $review = Review::create($reviewEntity->toArray());
        Cache::tags(['catalog', 'school', 'review', 'course'])->flush();

        $action = new ReviewGetAction($review->id);

        return $action->run();
    }
}
