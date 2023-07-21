<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Admin;

use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use Cache;
use Carbon\Carbon;

/**
 * Класс действия для создания отзывов.
 */
class ReviewCreateAction extends Action
{
    /**
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * ID курса.
     *
     * @var int|null
     */
    public ?int $course_id = null;

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
     * Отзыв.
     *
     * @var string|null
     */
    public ?string $review = null;

    /**
     * Достоинства.
     *
     * @var string|null
     */
    public ?string $advantages = null;

    /**
     * Недостатки.
     *
     * @var string|null
     */
    public ?string $disadvantages = null;

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
     * Дата добавления.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Источник.
     *
     * @var ?string
     */
    public ?string $source = null;

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
        $reviewEntity->course_id = $this->course_id;
        $reviewEntity->name = $this->name;
        $reviewEntity->title = Typography::process($this->title, true);
        $reviewEntity->review = Typography::process($this->review, true);
        $reviewEntity->advantages = Typography::process($this->advantages, true);
        $reviewEntity->disadvantages = Typography::process($this->disadvantages, true);
        $reviewEntity->rating = $this->rating;
        $reviewEntity->status = $this->status;
        $reviewEntity->created_at = $this->created_at;
        $reviewEntity->source = $this->source;

        $review = Review::create($reviewEntity->toArray());
        Cache::tags(['catalog', 'school', 'review', 'course'])->flush();

        $action = app(ReviewGetAction::class);
        $action->id = $review->id;

        return $action->run();
    }
}
