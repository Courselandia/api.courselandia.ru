<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Repositories\Review;
use Cache;

/**
 * Класс действия для обновления статуса отзывов.
 */
class ReviewUpdateStatusAction extends Action
{
    /**
     * Репозиторий отзывов.
     *
     * @var Review
     */
    private Review $review;

    /**
     * ID отзывов.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Конструктор.
     *
     * @param  Review  $review  Репозиторий отзывов.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Метод запуска логики.
     *
     * @return ReviewEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): ReviewEntity
    {
        $action = app(ReviewGetAction::class);
        $action->id = $this->id;
        $reviewEntity = $action->run();

        if ($reviewEntity) {
            $reviewEntity->status = $this->status;
            $this->review->update($this->id, $reviewEntity);
            Cache::tags(['catalog', 'profession', 'review'])->flush();

            return $reviewEntity;
        }

        throw new RecordNotExistException(
            trans('review::actions.admin.reviewUpdateStatusAction.notExistReview')
        );
    }
}
