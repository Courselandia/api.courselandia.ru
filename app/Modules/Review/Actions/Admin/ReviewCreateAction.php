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
use App\Modules\Review\Enums\Level;
use App\Modules\Review\Repositories\Review;
use Cache;
use ReflectionException;

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
    private Review $reviewRep;

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
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): ReviewEntity
    {
        $reviewEntity = new ReviewEntity();
        $reviewEntity->level = $this->level;
        $reviewEntity->review = $this->review;
        $reviewEntity->profession_id = $this->profession_id;
        $reviewEntity->status = $this->status;

        $id = $this->reviewRep->create($reviewEntity);
        Cache::tags(['catalog', 'profession', 'review'])->flush();

        $action = app(ReviewGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
