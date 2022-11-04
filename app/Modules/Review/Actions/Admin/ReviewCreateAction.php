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
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Repositories\Review;
use Cache;

/**
 * Класс действия для создания отзывов.
 */
class ReviewCreateAction extends Action
{
    /**
     * Репозиторий отзывов.
     *
     * @var Review
     */
    private Review $review;

    /**
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * Имя автора.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Текст.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Рейтинг.
     *
     * @var int|null
     */
    public ?int $rating = null;

    /**
     * Статус.
     *
     * @var Status|null
     */
    public ?Status $status = null;

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
     * @throws ParameterInvalidException
     */
    public function run(): ReviewEntity
    {
        $reviewEntity = new ReviewEntity();
        $reviewEntity->school_id = $this->school_id;
        $reviewEntity->name = $this->name;
        $reviewEntity->title = $this->title;
        $reviewEntity->text = $this->text;
        $reviewEntity->rating = $this->rating;
        $reviewEntity->status = $this->status;

        $id = $this->review->create($reviewEntity);
        Cache::tags(['catalog', 'school', 'review'])->flush();

        $action = app(ReviewGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
