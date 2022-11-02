<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Repositories\Review;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения отзывов.
 */
class ReviewGetAction extends Action
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
     * @return ReviewEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?ReviewEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'profession',
            ]);

        $cacheKey = Util::getKey('review', $query);

        return Cache::tags(['catalog', 'profession', 'review'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->review->get($query);
            }
        );
    }
}
