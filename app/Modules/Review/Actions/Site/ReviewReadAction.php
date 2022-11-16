<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Site;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Enums\SortDirection;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Review\Repositories\Review;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use Util;

/**
 * Класс действия для чтения отзывов.
 */
class ReviewReadAction extends Action
{
    /**
     * Репозиторий отзывов.
     *
     * @var Review
     */
    private Review $review;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

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
            ->setSorts($this->sorts)
            ->setOffset($this->offset)
            ->setLimit($this->limit)
            ->setRelations([
                'school',
            ])
            ->setActive(true);

        $cacheKey = Util::getKey('review', 'read', 'count', $query);

        return Cache::tags(['catalog', 'school', 'review', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return [
                    'data' => $this->review->read($query),
                    'total' => $this->review->count($query),
                ];
            }
        );
    }
}
