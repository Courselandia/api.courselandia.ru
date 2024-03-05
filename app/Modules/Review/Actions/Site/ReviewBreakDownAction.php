<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Site;

use Cache;
use DB;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use App\Modules\Review\Values\ReviewBreakDown;

/**
 * Класс действия для получения разбивки по рейтингу.
 */
class ReviewBreakDownAction extends Action
{
    /**
     * ID школы.
     *
     * @var int
     */
    private int $school_id;

    /**
     * @param int $school_id ID школы.
     */
    public function __construct(int $school_id)
    {
        $this->school_id = $school_id;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): array
    {
        $cacheKey = Util::getKey('review', 'site', 'breakDown');

        return Cache::tags(['catalog', 'review'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $reviews = Review::where('school_id', $this->school_id)
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->select([
                        DB::raw('ROUND(rating) as rating'),
                        DB::raw('COUNT(rating) as amount'),
                    ])
                    ->groupBy('rating')
                    ->get();

                return $reviews->map(function ($item) {
                    return new ReviewBreakDown($item->rating, $item->amount);
                })->toArray();
            }
        );
    }
}
