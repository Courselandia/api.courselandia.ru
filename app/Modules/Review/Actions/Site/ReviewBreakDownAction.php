<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Site;

use App\Models\Entity;
use App\Modules\Review\Enums\Status;
use DB;
use Util;
use Cache;
use App\Models\Enums\CacheTime;
use App\Modules\Review\Entities\ReviewBreakDown;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Review\Models\Review;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс действия для получения разбивки по рейтингу.
 */
class ReviewBreakDownAction extends Action
{
    /**
     * ID школа.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $cacheKey = Util::getKey('review', 'site', 'breakDown');

        return Cache::tags(['catalog', 'school', 'review', 'course'])->remember(
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

                return Entity::toEntities($reviews->toArray(), new ReviewBreakDown());
            }
        );
    }
}
