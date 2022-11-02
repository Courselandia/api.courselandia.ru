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
use App\Modules\Review\Enums\Level;
use App\Modules\Review\Repositories\Review;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления отзывов.
 */
class ReviewUpdateAction extends Action
{
    /**
     * Репозиторий отзывов.
     *
     * @var Review
     */
    private Review $reviewRep;

    /**
     * ID отзывов.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID профессии.
     *
     * @var int|null
     */
    public ?int $profession_id = null;

    /**
     * Уровень.
     *
     * @var Level|null
     */
    public ?Level $level = null;

    /**
     * Отзыв.
     *
     * @var int|null
     */
    public ?int $review = null;

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
        $this->reviewRep = $review;
    }

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
            $reviewEntity->level = $this->level;
            $reviewEntity->review = $this->review;
            $reviewEntity->profession_id = $this->profession_id;
            $reviewEntity->status = $this->status;

            $this->reviewRep->update($this->id, $reviewEntity);
            Cache::tags(['catalog', 'profession', 'review'])->flush();

            $action = app(ReviewGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('review::actions.admin.reviewUpdateAction.notExistReview')
        );
    }
}
