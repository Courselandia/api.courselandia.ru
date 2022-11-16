<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Site;

use App\Models\Enums\OperatorQuery;
use App\Modules\Review\Enums\Status;
use DB;
use Util;
use Cache;
use App\Models\Enums\CacheTime;
use App\Modules\Review\Entities\ReviewBreakDown;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Review\Repositories\Review;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс действия для получения разбивки по рейтингу.
 */
class ReviewBreakDownAction extends Action
{
    /**
     * Репозиторий отзывов.
     *
     * @var Review
     */
    private Review $review;

    /**
     * ID школа.
     *
     * @var int|null
     */
    public ?int $school_id = null;

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
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $query = new RepositoryQueryBuilder();
        $query->addCondition(new RepositoryCondition('school_id', $this->school_id))
            ->addCondition(new RepositoryCondition('status', Status::ACTIVE->value))
            ->addCondition(new RepositoryCondition('status', true, OperatorQuery::EQUAL, 'school'))
            ->setSelects([
                DB::raw('ROUND(rating) as rating'),
                DB::raw('COUNT(rating) as amount'),
            ])
            ->addGroup('rating');

        $cacheKey = Util::getKey('review', 'creakDown', $query);

        return Cache::tags(['catalog', 'school', 'review', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->review->read($query, new ReviewBreakDown());
            }
        );
    }
}
