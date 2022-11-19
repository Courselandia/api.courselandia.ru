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
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use Cache;

/**
 * Класс действия для обновления отзывов.
 */
class ReviewUpdateAction extends Action
{
    /**
     * ID отзывов.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * Метод запуска логики.
     *
     * @return ReviewEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): ReviewEntity
    {
        $action = app(ReviewGetAction::class);
        $action->id = $this->id;
        $reviewEntity = $action->run();

        if ($reviewEntity) {
            $reviewEntity->id = $this->id;
            $reviewEntity->school_id = $this->school_id;
            $reviewEntity->course_id = $this->course_id;
            $reviewEntity->name = $this->name;
            $reviewEntity->title = $this->title;
            $reviewEntity->text = $this->text;
            $reviewEntity->rating = $this->rating;
            $reviewEntity->status = $this->status;

            Review::find($this->id)->update($reviewEntity->toArray());
            Cache::tags(['catalog', 'school', 'review', 'course'])->flush();

            $action = app(ReviewGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('review::actions.admin.reviewUpdateAction.notExistReview')
        );
    }
}
