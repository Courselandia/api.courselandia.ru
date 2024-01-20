<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Models\Review;
use Cache;
use Util;

/**
 * Класс действия для получения отзывов.
 */
class ReviewGetAction extends Action
{
    /**
     * ID отзыва.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID отзыва.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return ReviewEntity|null Вернет результаты исполнения.
     */
    public function run(): ?ReviewEntity
    {
        $cacheKey = Util::getKey('review', $this->id, 'school', 'course');

        return Cache::tags(['catalog', 'school', 'review', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $review = Review::where('id', $this->id)
                    ->with([
                        'school',
                        'course',
                    ])->first();

                return $review ? ReviewEntity::from($review->toArray()) : null;
            }
        );
    }
}
